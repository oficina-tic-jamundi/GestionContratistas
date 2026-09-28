<?php

declare(strict_types=1);

namespace Sigcon\Console\Commands;

use Sigcon\Config\AppEnvironment;
use Sigcon\Config\Settings;
use Sigcon\Console\Command;
use Sigcon\Console\Output;
use Sigcon\DTOs\RoleData;
use Sigcon\Repositories\RoleRepository;
use Sigcon\Services\Roles\RoleService;
use Sigcon\DTOs\ContractData;
use Sigcon\DTOs\ContractorData;
use Sigcon\DTOs\CreateUserData;
use Sigcon\DTOs\DepartmentData;
use Sigcon\Helpers\Clock;
use Sigcon\Models\ActiveStatus;
use Sigcon\Models\ContractStatus;
use Sigcon\Models\Contractor;
use Sigcon\Helpers\Uuid;
use Sigcon\Models\ActivityLevel;
use Sigcon\Models\ActivityPriority;
use Sigcon\Repositories\ActivityRepository;
use Sigcon\Repositories\ContractorRepository;
use Sigcon\Repositories\ContractRepository;
use Sigcon\Repositories\ContractScope;
use Sigcon\Repositories\DepartmentRepository;
use Sigcon\Repositories\UserRepository;
use Sigcon\Security\PasswordHasher;
use Sigcon\Services\Contractors\ContractorService;
use Sigcon\Services\Contracts\ContractService;
use Sigcon\Services\Departments\DepartmentService;
use Sigcon\Services\History\HistoryRecorder;
use Sigcon\Services\Users\UserService;

/**
 * Carga datos FICTICIOS de desarrollo. Se niega a ejecutarse fuera de APP_ENV=development.
 * Es idempotente: si los datos ya existen, no los duplica.
 *
 * - Correos con el dominio reservado example.test (RFC 2606): nunca corresponden a personas reales.
 * - Documentos de identidad inventados (no válidos para ninguna persona real).
 * - Todos los usuarios comparten una contraseña de desarrollo conocida, documentada en el README.
 */
final class SeedDevelopmentCommand implements Command
{
    public const DEV_PASSWORD = 'Sigcon-Desarrollo-2026';

    private const USERS = [
        ['first_name' => 'Ana', 'last_name' => 'Administradora', 'email' => 'admin@example.test', 'roles' => ['admin']],
        ['first_name' => 'Samuel', 'last_name' => 'Supervisor', 'email' => 'supervisor@example.test', 'roles' => ['supervisor']],
        ['first_name' => 'Carla', 'last_name' => 'Contratista', 'email' => 'contratista@example.test', 'roles' => ['contractor']],
        ['first_name' => 'Diego', 'last_name' => 'Contratista Dos', 'email' => 'contratista2@example.test', 'roles' => ['contractor']],
        ['first_name' => 'Olga', 'last_name' => 'Ordenadora', 'email' => 'ordenador@example.test', 'roles' => ['demo_ordenador']],
        ['first_name' => 'Tomás', 'last_name' => 'Tesorero', 'email' => 'tesoreria@example.test', 'roles' => ['demo_tesoreria']],
    ];

    private const DEPARTMENTS = [
        ['code' => 'DEMO-PLAN', 'name' => 'Secretaría de Planeación (demo)'],
        ['code' => 'DEMO-EDU', 'name' => 'Secretaría de Educación (demo)'],
        ['code' => 'DEMO-SAL', 'name' => 'Secretaría de Salud (demo)'],
    ];

    /**
     * Roles de EJEMPLO para probar la separación de funciones en pagos (ADR-018). No son la
     * estructura institucional: la Alcaldía define sus roles reales desde "Roles y permisos".
     */
    private const DEMO_ROLES = [
        ['code' => 'demo_ordenador', 'name' => 'Ordenador del gasto (demo)', 'description' => 'Rol de ejemplo: aprueba pagos.', 'permissions' => ['payments.approve', 'contracts.view_all']],
        ['code' => 'demo_tesoreria', 'name' => 'Tesorería (demo)', 'description' => 'Rol de ejemplo: registra pagos efectuados.', 'permissions' => ['payments.register', 'contracts.view_all']],
    ];

    public function __construct(
        private readonly Settings $settings,
        private readonly RoleService $roleService,
        private readonly RoleRepository $roles,
        private readonly UserService $userService,
        private readonly UserRepository $users,
        private readonly PasswordHasher $hasher,
        private readonly DepartmentService $departmentService,
        private readonly DepartmentRepository $departments,
        private readonly ContractorService $contractorService,
        private readonly ContractService $contractService,
        private readonly ContractorRepository $contractorRepository,
        private readonly ContractRepository $contracts,
        private readonly HistoryRecorder $history,
        private readonly ActivityRepository $activities,
        private readonly Clock $clock,
    ) {
    }

    public function name(): string
    {
        return 'db:seed';
    }

    public function description(): string
    {
        return 'Carga datos ficticios de desarrollo (solo APP_ENV=development).';
    }

    public function run(array $arguments, Output $output): int
    {
        if ($this->settings->environment !== AppEnvironment::Development) {
            $output->error('db:seed solo puede ejecutarse con APP_ENV=development.');

            return 1;
        }

        $this->seedRoles($output);
        $this->seedUsers($output);
        $departments = $this->seedDepartments($output);
        $this->seedContracts($output, $departments);
        $this->seedObligations($output);
        $output->line(sprintf('Contraseña de todos los usuarios de desarrollo: %s', self::DEV_PASSWORD));

        return 0;
    }

    private function seedRoles(Output $output): void
    {
        foreach (self::DEMO_ROLES as $data) {
            if ($this->roles->findByCode($data['code']) !== null) {
                $output->line('  existe:  rol ' . $data['code']);
                continue;
            }
            $this->roleService->create(RoleData::fromBody($data, withCode: true));
            $output->line('  creado:  rol ' . $data['code']);
        }
    }

    private function seedUsers(Output $output): void
    {
        $hash = $this->hasher->hash(self::DEV_PASSWORD);
        foreach (self::USERS as $data) {
            if ($this->users->findByEmail($data['email']) !== null) {
                $output->line('  existe:  ' . $data['email']);
                continue;
            }
            $user = $this->userService->create(CreateUserData::fromBody($data))['user'];
            // Contraseña de desarrollo conocida y sin cambio obligatorio, para agilizar las pruebas manuales.
            $this->users->updatePassword($user->id, $hash, false, $this->clock->now(), null);
            $output->line('  creado:  ' . $data['email']);
        }
    }

    /** @return array<string, string> código => uuid */
    private function seedDepartments(Output $output): array
    {
        $existing = [];
        foreach ($this->departments->all(ActiveStatus::Active) as $department) {
            $existing[$department->code] = $department->uuid;
        }
        foreach (self::DEPARTMENTS as $data) {
            if (!isset($existing[$data['code']])) {
                $existing[$data['code']] = $this->departmentService->create(DepartmentData::fromBody($data))->uuid;
                $output->line('  dependencia: ' . $data['name']);
            }
        }

        return $existing;
    }

    /**
     * Obligaciones ficticias para los contratos de demostración que aún no tengan.
     * Se insertan directamente (solo desarrollo): en la aplicación, las obligaciones se
     * registran con el contrato en borrador (ADR-013).
     */
    private function seedObligations(Output $output): void
    {
        $obligations = [
            'Apoyar la formulación y seguimiento de los proyectos de inversión asignados (demo).',
            'Elaborar los informes técnicos que le sean solicitados por la supervisión (demo).',
            'Asistir a las reuniones y mesas de trabajo convocadas por la dependencia (demo).',
        ];
        foreach (['DEMO-CPS-001-2026', 'DEMO-CPS-002-2026', 'DEMO-CPS-003-2026'] as $number) {
            $page = $this->contracts->paginate(
                new \Sigcon\DTOs\ContractFilters(search: $number),
                \Sigcon\Http\PageRequest::fromQuery([], ContractRepository::SORTABLE, 'number'),
                ContractScope::unrestricted(),
            );
            $contract = $page->items[0] ?? null;
            if ($contract === null || $this->activities->forContract($contract->id) !== []) {
                continue;
            }
            // Prioridades de ejemplo (ADR-021): en producción las asigna la Alcaldía.
            $priorities = [ActivityPriority::High, ActivityPriority::Medium, ActivityPriority::Low];
            foreach ($obligations as $i => $title) {
                $id = $this->activities->create(Uuid::v4(), $contract->id, null, ActivityLevel::Obligation, $title, null, '1.00', null, $this->clock->now(), null);
                $this->activities->setPriority($id, $priorities[$i], $this->clock->now(), null);
            }
            $output->line('  obligaciones: ' . $number);
        }
    }

    /** Crea el contratista o devuelve el existente con el mismo documento (idempotencia). */
    private function contractor(ContractorData $data): Contractor
    {
        return $this->contractorRepository->findByDocument($data->documentType, $data->documentNumber)
            ?? $this->contractorService->create($data);
    }

    /** @param array<string, string> $departments */
    private function seedContracts(Output $output, array $departments): void
    {
        if ($this->contracts->numberExists('DEMO-CPS-001-2026')) {
            $output->line('  contratos de demostración ya existen');

            return;
        }
        $supervisor = $this->users->findByEmail('supervisor@example.test');
        $carla = $this->users->findByEmail('contratista@example.test');
        $diego = $this->users->findByEmail('contratista2@example.test');
        if ($supervisor === null || $carla === null || $diego === null) {
            return;
        }

        $carlaContractor = $this->contractor(ContractorData::fromBody([
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '99000001',
            'name' => 'Carla Contratista', 'email' => 'contratista@example.test', 'user' => $carla->uuid,
        ]));
        $diegoContractor = $this->contractor(ContractorData::fromBody([
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '99000002',
            'name' => 'Diego Contratista Dos', 'email' => 'contratista2@example.test', 'user' => $diego->uuid,
        ]));
        $this->contractor(ContractorData::fromBody([
            'person_type' => 'juridica', 'document_type' => 'NIT', 'document_number' => '900000001', 'verification_digit' => '2',
            'name' => 'Empresa Ficticia de Servicios S.A.S. (demo)', 'email' => 'contacto@example.test',
        ]));

        $contracts = [
            ['DEMO-CPS-001-2026', $carlaContractor->uuid, 'DEMO-PLAN', 'Prestación de servicios profesionales para apoyar la formulación de proyectos de inversión (demo).', '2026-01-15', '2026-12-15', '42000000', true],
            ['DEMO-CPS-002-2026', $diegoContractor->uuid, 'DEMO-EDU', 'Prestación de servicios de apoyo a la gestión en la Secretaría de Educación (demo).', '2026-02-01', '2026-10-31', '27000000', true],
            ['DEMO-CPS-003-2026', $carlaContractor->uuid, 'DEMO-SAL', 'Prestación de servicios profesionales en salud pública (demo, en borrador).', '2026-11-01', '2027-04-30', '30000000', false],
        ];
        foreach ($contracts as [$number, $contractor, $department, $object, $start, $end, $value, $activate]) {
            $contract = $this->contractService->create(ContractData::fromBody([
                'contract_number' => $number, 'object' => $object, 'contractor' => $contractor,
                'department' => $departments[$department], 'supervisor' => $supervisor->uuid,
                'start_date' => $start, 'end_date' => $end, 'total_value' => $value,
            ]));
            if ($activate) {
                // En consola no hay usuario autenticado: se activa directamente, dejando constancia en el historial.
                $this->contracts->setStatus($contract->id, ContractStatus::Active, $this->clock->now(), null);
                $this->history->record(HistoryRecorder::CONTRACT, $contract->id, 'status_changed', 'Contrato activado (datos de desarrollo)', null, 'draft', 'active');
            }
            $output->line('  contrato: ' . $number);
        }

        // Verificación rápida de consistencia.
        $total = $this->contracts->paginate(new \Sigcon\DTOs\ContractFilters(), \Sigcon\Http\PageRequest::fromQuery([], ContractRepository::SORTABLE, 'number'), ContractScope::unrestricted())->total;
        $output->line(sprintf('  contratos en la base: %d', $total));
    }
}
