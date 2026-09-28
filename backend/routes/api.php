<?php

declare(strict_types=1);

/**
 * Rutas de la API y matriz de permisos.
 *
 * Grupos:
 *  - Públicas: sin sesión.
 *  - /auth (sesión): perfil, cierre de sesión y cambio de contraseña. Permitidas aun con
 *    contraseña temporal pendiente de cambio.
 *  - Protegidas: sesión + CSRF + contraseña definitiva + permiso específico por ruta.
 *
 * Slim ejecuta el middleware de un grupo en orden inverso al registro: el último ->add()
 * se ejecuta primero. Por eso Authenticate se agrega al final.
 *
 * Toda petición que modifica datos pasa además por OriginCheckMiddleware (global, ver Kernel).
 */

use Sigcon\Controllers\ActivityController;
use Sigcon\Controllers\AuditLogController;
use Sigcon\Controllers\AuthController;
use Sigcon\Controllers\ContractController;
use Sigcon\Controllers\ContractorController;
use Sigcon\Controllers\DashboardController;
use Sigcon\Controllers\DepartmentController;
use Sigcon\Controllers\DocumentController;
use Sigcon\Controllers\EvidenceController;
use Sigcon\Controllers\HealthController;
use Sigcon\Controllers\HistoryFeedController;
use Sigcon\Controllers\JobController;
use Sigcon\Controllers\NotificationController;
use Sigcon\Controllers\PaymentController;
use Sigcon\Controllers\ReportController;
use Sigcon\Controllers\RoleController;
use Sigcon\Controllers\StatisticsController;
use Sigcon\Controllers\UserController;
use Sigcon\Middleware\AuthenticateMiddleware;
use Sigcon\Middleware\AuthorizeAnyMiddleware;
use Sigcon\Middleware\AuthorizeMiddleware;
use Sigcon\Middleware\CsrfMiddleware;
use Sigcon\Middleware\RequirePasswordChangedMiddleware;
use Sigcon\Middleware\RateLimitMiddleware;
use Sigcon\Security\AuthContext;
use Sigcon\Security\RateLimiter;
use Sigcon\Security\Permission as P;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    /** @var AuthContext $auth */
    $auth = $app->getContainer()?->get(AuthContext::class);
    $can = static fn (P ...$permissions) => new AuthorizeMiddleware($auth, ...$permissions);
    $canAny = static fn (P ...$permissions) => new AuthorizeAnyMiddleware($auth, ...$permissions);
    /** @var RateLimiter $limiter */
    $limiter = $app->getContainer()?->get(RateLimiter::class);
    // Límites por usuario para operaciones costosas (disco o CPU): cantidad por ventana de 10 minutos.
    $limit = static fn (string $operation, int $max) => new RateLimitMiddleware($limiter, $auth, $operation, $max, 600);

    $app->group('/v1', function (RouteCollectorProxy $v1) use ($can, $canAny, $limit): void {
        // --- Públicas ---
        $v1->get('/health', [HealthController::class, 'show']);
        $v1->post('/auth/login', [AuthController::class, 'login']);

        // --- Sesión (permitidas con contraseña temporal) ---
        $v1->group('/auth', function (RouteCollectorProxy $group): void {
            $group->get('/me', [AuthController::class, 'me']);
            $group->post('/logout', [AuthController::class, 'logout']);
            $group->post('/password', [AuthController::class, 'changePassword']);
        })->add(CsrfMiddleware::class)->add(AuthenticateMiddleware::class);

        // --- Protegidas ---
        $v1->group('', function (RouteCollectorProxy $group) use ($can, $canAny, $limit): void {
            $group->get('/users', [UserController::class, 'index'])->add($can(P::UsersView));
            $group->post('/users', [UserController::class, 'store'])->add($can(P::UsersCreate));
            $group->get('/users/{uuid}', [UserController::class, 'show'])->add($can(P::UsersView));
            // PATCH exige users.update para datos y/o users.assign_roles para roles (lo verifica el controlador
            // según los campos enviados); aquí se exige al menos poder ver usuarios.
            $group->patch('/users/{uuid}', [UserController::class, 'update'])->add($can(P::UsersView));
            $group->post('/users/{uuid}/activate', [UserController::class, 'activate'])->add($can(P::UsersDeactivate));
            $group->post('/users/{uuid}/deactivate', [UserController::class, 'deactivate'])->add($can(P::UsersDeactivate));
            $group->post('/users/{uuid}/reset-password', [UserController::class, 'resetPassword'])->add($can(P::UsersResetPassword));

            $group->get('/roles', [RoleController::class, 'index'])->add($can(P::RolesView));
            $group->post('/roles', [RoleController::class, 'store'])->add($can(P::RolesManage));
            $group->put('/roles/{code}', [RoleController::class, 'update'])->add($can(P::RolesManage));
            $group->delete('/roles/{code}', [RoleController::class, 'destroy'])->add($can(P::RolesManage));
            $group->get('/permissions', [RoleController::class, 'permissions'])->add($can(P::RolesView));

            $group->get('/audit-logs', [AuditLogController::class, 'index'])->add($can(P::AuditView));
            $group->get('/audit-logs/actions', [AuditLogController::class, 'actions'])->add($can(P::AuditView));

            // --- Dependencias ---
            $group->get('/departments', [DepartmentController::class, 'index'])->add($canAny(P::DepartmentsView, P::ContractsManage, P::UsersView));
            $group->post('/departments', [DepartmentController::class, 'store'])->add($can(P::DepartmentsManage));
            $group->put('/departments/{uuid}', [DepartmentController::class, 'update'])->add($can(P::DepartmentsManage));
            $group->post('/departments/{uuid}/activate', [DepartmentController::class, 'activate'])->add($can(P::DepartmentsManage));
            $group->post('/departments/{uuid}/deactivate', [DepartmentController::class, 'deactivate'])->add($can(P::DepartmentsManage));

            // --- Contratistas ---
            $group->get('/contractors', [ContractorController::class, 'index'])->add($canAny(P::ContractorsView, P::ContractsViewAssigned));
            $group->post('/contractors', [ContractorController::class, 'store'])->add($can(P::ContractorsManage));
            $group->get('/contractors/{uuid}', [ContractorController::class, 'show'])->add($canAny(P::ContractorsView, P::ContractsViewAssigned));
            $group->put('/contractors/{uuid}', [ContractorController::class, 'update'])->add($can(P::ContractorsManage));
            $group->post('/contractors/{uuid}/activate', [ContractorController::class, 'activate'])->add($can(P::ContractorsManage));
            $group->post('/contractors/{uuid}/deactivate', [ContractorController::class, 'deactivate'])->add($can(P::ContractorsManage));

            // --- Contratos (lectura filtrada por alcance en ContractService: todos / asignados / propios) ---
            $viewContracts = $canAny(P::ContractsViewAll, P::ContractsViewAssigned, P::ContractsViewOwn);
            $group->get('/contracts', [ContractController::class, 'index'])->add($viewContracts);
            $group->post('/contracts', [ContractController::class, 'store'])->add($can(P::ContractsManage));
            $group->get('/contracts/{uuid}', [ContractController::class, 'show'])->add($viewContracts);
            $group->get('/contracts/{uuid}/history', [ContractController::class, 'history'])->add($viewContracts);
            $group->put('/contracts/{uuid}', [ContractController::class, 'update'])->add($can(P::ContractsManage));
            $group->delete('/contracts/{uuid}', [ContractController::class, 'destroy'])->add($can(P::ContractsManage));
            $group->post('/contracts/{uuid}/transitions', [ContractController::class, 'transition'])->add($can(P::ContractsManage));
            $group->post('/contracts/{uuid}/supervisor', [ContractController::class, 'changeSupervisor'])->add($can(P::ContractsManage));
            $group->get('/supervisors', [ContractController::class, 'supervisors'])->add($can(P::ContractsManage));

            // --- Obligaciones, tareas, subtareas y avance (reglas finas en ActivityService, ADR-013) ---
            $group->get('/contracts/{uuid}/activities', [ActivityController::class, 'tree'])->add($viewContracts);
            $group->post('/contracts/{uuid}/obligations', [ActivityController::class, 'createObligation'])->add($can(P::ContractsManage));
            // Obligaciones propuestas desde el contrato firmado y su registro en bloque (ADR-022).
            $group->get('/contracts/{uuid}/obligations/suggestions', [ActivityController::class, 'suggestObligations'])->add($can(P::ContractsManage));
            $group->post('/contracts/{uuid}/obligations/bulk', [ActivityController::class, 'createObligations'])->add($can(P::ContractsManage));
            $group->post('/activities/{uuid}/children', [ActivityController::class, 'createChild'])->add($canAny(P::ContractsManage, P::ActivitiesPlan));
            $group->put('/activities/{uuid}', [ActivityController::class, 'update'])->add($canAny(P::ContractsManage, P::ActivitiesPlan));
            $group->delete('/activities/{uuid}', [ActivityController::class, 'destroy'])->add($canAny(P::ContractsManage, P::ActivitiesPlan));
            $group->post('/activities/{uuid}/progress', [ActivityController::class, 'recordProgress'])->add($can(P::ActivitiesProgress));
            $group->get('/activities/{uuid}', [ActivityController::class, 'show'])->add($viewContracts);
            $group->get('/activities/{uuid}/progress', [ActivityController::class, 'progressHistory'])->add($viewContracts);
            $group->put('/activities/{uuid}/priority', [ActivityController::class, 'setPriority'])->add($can(P::ContractsManage));

            // --- Documentos (alcance del contrato; reglas de carga/retiro en DocumentService, ADR-014) ---
            $group->get('/contracts/{uuid}/documents', [DocumentController::class, 'index'])->add($viewContracts);
            $group->post('/contracts/{uuid}/documents', [DocumentController::class, 'store'])->add($canAny(P::ContractsManage, P::ContractsSupervise, P::ActivitiesExecute))->add($limit('upload', 60));
            $group->get('/documents/{uuid}/download', [DocumentController::class, 'download'])->add($viewContracts);
            $group->post('/documents/{uuid}/withdraw', [DocumentController::class, 'withdraw'])->add($canAny(P::ContractsManage, P::ContractsSupervise, P::ActivitiesExecute, P::ReportsCreate));

            // --- Informes (alcance del contrato + reglas de autor/supervisor asignado en ReportAccess, ADR-015) ---
            $group->get('/reports', [ReportController::class, 'index'])->add($viewContracts);
            $group->post('/contracts/{uuid}/reports', [ReportController::class, 'store'])->add($can(P::ReportsCreate));
            $group->get('/reports/{uuid}', [ReportController::class, 'show'])->add($viewContracts);
            $group->put('/reports/{uuid}', [ReportController::class, 'update'])->add($can(P::ReportsCreate));
            $group->post('/reports/{uuid}/submit', [ReportController::class, 'submit'])->add($can(P::ReportsCreate));
            $group->post('/reports/{uuid}/start-review', [ReportController::class, 'startReview'])->add($can(P::ReportsReview));
            $group->post('/reports/{uuid}/observe', [ReportController::class, 'observe'])->add($can(P::ReportsReview));
            $group->post('/reports/{uuid}/approve', [ReportController::class, 'approve'])->add($can(P::ReportsApprove));
            $group->post('/reports/{uuid}/reject', [ReportController::class, 'reject'])->add($can(P::ReportsApprove));
            $group->post('/reports/{uuid}/reopen', [ReportController::class, 'reopen'])->add($can(P::ReportsReopen));
            $group->get('/reports/{uuid}/versions/{version}', [ReportController::class, 'version'])->add($viewContracts);
            $group->get('/reports/{uuid}/history', [ReportController::class, 'history'])->add($viewContracts);
            $group->get('/reports/{uuid}/versions/{version}/pdf', [ReportController::class, 'pdf'])->add($viewContracts)->add($limit('pdf', 120));
            $group->get('/reports/{uuid}/documents', [DocumentController::class, 'reportIndex'])->add($viewContracts);
            $group->post('/reports/{uuid}/documents', [DocumentController::class, 'reportStore'])->add($can(P::ReportsCreate))->add($limit('upload', 60));
            $group->post('/reports/{uuid}/composed-documents', [DocumentController::class, 'reportCompose'])->add($can(P::ReportsCreate))->add($limit('upload', 60));

            // --- Evidencias fotográficas (alcance del contrato; reglas en EvidenceService, ADR-006/016) ---
            $group->get('/contracts/{uuid}/evidences', [EvidenceController::class, 'index'])->add($viewContracts);
            $group->post('/activities/{uuid}/evidences', [EvidenceController::class, 'store'])->add($can(P::EvidenceCapture))->add($limit('evidence', 60));
            $group->get('/evidences/{uuid}/image', [EvidenceController::class, 'image'])->add($viewContracts);
            $group->post('/evidences/{uuid}/withdraw', [EvidenceController::class, 'withdraw'])->add($canAny(P::ContractsManage, P::EvidenceCapture));

            // --- Pagos (alcance del contrato; permisos y separación de funciones en PaymentService, ADR-018) ---
            $group->get('/payments', [PaymentController::class, 'index'])->add($viewContracts);
            $group->post('/payments', [PaymentController::class, 'store'])->add($can(P::PaymentsManage));
            $group->get('/payment-rules', [PaymentController::class, 'rules'])->add($canAny(P::PaymentsConfigure, P::PaymentsManage, P::PaymentsApprove));
            $group->put('/payment-rules/{code}', [PaymentController::class, 'configureRule'])->add($can(P::PaymentsConfigure));
            $group->get('/contracts/{uuid}/budget', [PaymentController::class, 'budget'])->add($viewContracts);
            $group->get('/payments/{uuid}', [PaymentController::class, 'show'])->add($viewContracts);
            $group->put('/payments/{uuid}', [PaymentController::class, 'update'])->add($can(P::PaymentsManage));
            $group->get('/payments/{uuid}/history', [PaymentController::class, 'history'])->add($viewContracts);
            $group->post('/payments/{uuid}/evaluate', [PaymentController::class, 'evaluate'])->add($can(P::PaymentsManage));
            $group->post('/payments/{uuid}/submit', [PaymentController::class, 'submit'])->add($can(P::PaymentsManage));
            $group->post('/payments/{uuid}/approve', [PaymentController::class, 'approve'])->add($can(P::PaymentsApprove));
            $group->post('/payments/{uuid}/return', [PaymentController::class, 'returnToDraft'])->add($can(P::PaymentsApprove));
            $group->post('/payments/{uuid}/paid', [PaymentController::class, 'registerPaid'])->add($can(P::PaymentsRegister));
            $group->post('/payments/{uuid}/cancel', [PaymentController::class, 'cancel'])->add($can(P::PaymentsManage));

            // --- Tablero, notificaciones y reportes (ADR-019) ---
            // El tablero y las notificaciones son de cada usuario: basta con estar autenticado.
            $group->get('/dashboard', [DashboardController::class, 'index']);
            // Estadísticas de pagos por contrato (ADR-021).
            $group->get('/statistics/payments', [StatisticsController::class, 'payments'])
                ->add($canAny(P::ContractsViewAll, P::ContractsViewAssigned));
            // Historial cronológico con el alcance del usuario (ADR-021).
            $group->get('/history', [HistoryFeedController::class, 'index'])->add($viewContracts);
            $group->get('/notifications', [NotificationController::class, 'index']);
            $group->get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
            $group->post('/notifications/read-all', [NotificationController::class, 'markAllRead']);
            $group->post('/notifications/{uuid}/read', [NotificationController::class, 'markRead']);
            $group->get('/exports/contracts', [DashboardController::class, 'exportContracts'])->add($can(P::ExportsRun))->add($limit('export', 20));
            $group->get('/exports/payments', [DashboardController::class, 'exportPayments'])->add($can(P::ExportsRun))->add($limit('export', 20));

            // --- Cola de trabajos en segundo plano (ADR-005, ADR-017) ---
            $group->get('/jobs', [JobController::class, 'index'])->add($can(P::JobsManage));
            $group->get('/jobs/status', [JobController::class, 'status'])->add($can(P::JobsManage));
            $group->post('/jobs/{uuid}/retry', [JobController::class, 'retry'])->add($can(P::JobsManage));
        })
            ->add(RequirePasswordChangedMiddleware::class)
            ->add(CsrfMiddleware::class)
            ->add(AuthenticateMiddleware::class);
    });
};
