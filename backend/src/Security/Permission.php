<?php

declare(strict_types=1);

namespace Sigcon\Security;

/**
 * Catálogo central de permisos. Cada valor debe existir en la tabla `permissions`
 * (una prueba de integración verifica que coincidan). Los módulos nuevos agregan sus
 * casos aquí y una migración que los inserta.
 */
enum Permission: string
{
    case UsersView = 'users.view';
    case UsersCreate = 'users.create';
    case UsersUpdate = 'users.update';
    case UsersDeactivate = 'users.deactivate';
    case UsersResetPassword = 'users.reset_password';
    case UsersAssignRoles = 'users.assign_roles';
    case RolesView = 'roles.view';
    case RolesManage = 'roles.manage';
    case AuditView = 'audit.view';

    case DepartmentsView = 'departments.view';
    case DepartmentsManage = 'departments.manage';
    case ContractorsView = 'contractors.view';
    case ContractorsManage = 'contractors.manage';
    case ContractsViewAll = 'contracts.view_all';
    case ContractsViewAssigned = 'contracts.view_assigned';
    case ContractsViewOwn = 'contracts.view_own';
    case ContractsManage = 'contracts.manage';
    case ContractsSupervise = 'contracts.supervise';

    case ActivitiesExecute = 'activities.execute';
    case ActivitiesPlan = 'activities.plan';
    case ActivitiesProgress = 'activities.progress';

    case ReportsCreate = 'reports.create';
    case ReportsReview = 'reports.review';
    case ReportsApprove = 'reports.approve';
    case ReportsReopen = 'reports.reopen';

    case EvidenceCapture = 'evidence.capture';
    case EvidenceViewOriginal = 'evidence.view_original';

    case JobsManage = 'jobs.manage';

    case PaymentsManage = 'payments.manage';
    case PaymentsApprove = 'payments.approve';
    case PaymentsRegister = 'payments.register';
    case PaymentsConfigure = 'payments.configure';

    case ExportsRun = 'exports.run';
}
