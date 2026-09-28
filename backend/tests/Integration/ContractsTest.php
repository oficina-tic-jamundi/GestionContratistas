<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use PDO;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\IntegrationTestCase;

final class ContractsTest extends IntegrationTestCase
{
    private Browser $admin;
    private string $supervisorUuid;
    private string $departmentUuid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('admin@example.test', ['admin']);
        $this->supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');
        $this->departmentUuid = $this->createDepartment('SPLAN', 'Secretaría de Planeación');
    }

    private function createDepartment(string $code, string $name): string
    {
        $response = $this->admin->post('/departments', ['code' => $code, 'name' => $name]);
        self::assertStatus(201, $response);

        return self::json($response)['data']['uuid'];
    }

    /** @param array<string, mixed> $overrides */
    private function createContractor(array $overrides = []): string
    {
        $response = $this->admin->post('/contractors', $overrides + [
            'person_type' => 'natural',
            'document_type' => 'CC',
            'document_number' => (string) random_int(10000000, 99999999),
            'name' => 'Contratista Ficticio',
            'email' => 'contratista.ficticio@example.test',
        ]);
        self::assertStatus(201, $response);

        return self::json($response)['data']['uuid'];
    }

    /** @param array<string, mixed> $overrides */
    private function contractBody(string $contractorUuid, array $overrides = []): array
    {
        return $overrides + [
            'contract_number' => 'CPS-' . random_int(100, 999) . '-2026',
            'object' => 'Prestación de servicios profesionales para apoyar la gestión de prueba.',
            'contractor' => $contractorUuid,
            'department' => $this->departmentUuid,
            'supervisor' => $this->supervisorUuid,
            'signed_at' => '2026-01-10',
            'start_date' => '2026-01-15',
            'end_date' => '2026-12-15',
            'total_value' => '42000000',
        ];
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function createContract(string $contractorUuid, array $overrides = []): array
    {
        $response = $this->admin->post('/contracts', $this->contractBody($contractorUuid, $overrides));
        self::assertStatus(201, $response);

        return self::json($response)['data'];
    }

    private function transition(string $uuid, string $status, ?string $comment = null, ?Browser $as = null): \Psr\Http\Message\ResponseInterface
    {
        return ($as ?? $this->admin)->post("/contracts/{$uuid}/transitions", array_filter(['status' => $status, 'comment' => $comment]));
    }

    public function testDepartmentsAreUniqueAndInactiveOnesCannotReceiveContracts(): void
    {
        $duplicate = $this->admin->post('/departments', ['code' => 'splan', 'name' => 'Otra']);
        self::assertStatus(422, $duplicate);
        self::assertSame('code', self::json($duplicate)['errors'][0]['field']);

        self::assertStatus(200, $this->admin->post("/departments/{$this->departmentUuid}/deactivate"));
        $response = $this->admin->post('/contracts', $this->contractBody($this->createContractor()));

        self::assertStatus(422, $response);
        self::assertSame('department', self::json($response)['errors'][0]['field']);
    }

    public function testContractorDocumentRulesAndAccountLinking(): void
    {
        $this->createContractor(['document_number' => '12345678']);
        self::assertStatus(422, $this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '12.345.678', 'name' => 'Duplicado',
        ]));

        $company = $this->admin->post('/contractors', [
            'person_type' => 'juridica', 'document_type' => 'NIT', 'document_number' => '800197268', 'verification_digit' => '4', 'name' => 'Empresa Ficticia S.A.S.',
        ]);
        self::assertStatus(201, $company);
        self::assertSame('NIT 800197268-4', self::json($company)['data']['document']);

        // Solo cuentas con perfil de contratista, una sola vez.
        $contractorAccount = $this->createUser('cuenta.contratista@example.test', ['contractor']);
        $invalidLink = $this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '5555555', 'name' => 'Persona Equis', 'user' => $this->supervisorUuid,
        ]);
        self::assertSame('not_contractor', self::json($invalidLink)['errors'][0]['code']);
        $this->createContractor(['document_number' => '6666666', 'user' => $contractorAccount]);
        $secondLink = $this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '7777777', 'name' => 'Persona Ye', 'user' => $contractorAccount,
        ]);
        self::assertSame('already_linked', self::json($secondLink)['errors'][0]['code']);
    }

    public function testContractLifecycleWithHistory(): void
    {
        $contract = $this->createContract($this->createContractor());
        $uuid = $contract['uuid'];
        self::assertSame('draft', $contract['status']);
        self::assertSame('42000000.00', $contract['total_value']);
        self::assertSame([['status' => 'active', 'label' => 'Activo', 'requires_comment' => false]], $contract['actions']['transitions']);

        // Borrador editable; número único.
        self::assertStatus(200, $this->admin->put("/contracts/{$uuid}", $this->contractBody($contract['contractor']['uuid'], ['contract_number' => 'CPS-100-2026', 'total_value' => '45000000.50'])));
        $other = $this->createContract($this->createContractor());
        self::assertStatus(422, $this->admin->put("/contracts/{$other['uuid']}", $this->contractBody($other['contractor']['uuid'], ['contract_number' => 'CPS-100-2026'])));

        self::assertStatus(200, $this->transition($uuid, 'active'));
        self::assertStatus(409, $this->admin->put("/contracts/{$uuid}", $this->contractBody($contract['contractor']['uuid'])), 'Un contrato activo no se edita libremente');
        self::assertStatus(409, $this->admin->delete("/contracts/{$uuid}"), 'Solo se descartan borradores');

        $noComment = $this->transition($uuid, 'suspended');
        self::assertStatus(422, $noComment);
        self::assertSame('comment', self::json($noComment)['errors'][0]['field']);
        self::assertStatus(200, $this->transition($uuid, 'suspended', 'Suspensión por solicitud de las partes (acta ficticia).'));
        self::assertStatus(409, $this->transition($uuid, 'liquidated'), 'Transición no permitida');
        self::assertStatus(200, $this->transition($uuid, 'active', 'Reanudación según acta ficticia.'));
        self::assertStatus(200, $this->transition($uuid, 'terminated', 'Terminación por vencimiento del plazo.'));
        self::assertStatus(200, $this->transition($uuid, 'liquidated', 'Liquidación bilateral ficticia.'));
        self::assertStatus(200, $this->transition($uuid, 'archived'));
        self::assertSame([], self::json($this->admin->get("/contracts/{$uuid}"))['data']['actions']['transitions']);

        $history = self::json($this->admin->get("/contracts/{$uuid}/history"))['data'];
        self::assertSame(
            ['Contrato archivado', 'Contrato liquidado', 'Contrato terminado', 'Contrato reanudado', 'Contrato suspendido', 'Contrato activado', 'Datos del borrador modificados', 'Contrato registrado en borrador'],
            array_column($history, 'summary'),
        );
        self::assertSame('Suspensión por solicitud de las partes (acta ficticia).', $history[4]['comment']);
        self::assertSame('Prueba Admin', $history[0]['user']);

        $audit = (int) $this->pdo()->query("SELECT COUNT(*) FROM audit_logs WHERE action = 'contract.status_changed'")?->fetchColumn();
        self::assertSame(6, $audit);
    }

    public function testActivationRequiresEligibleSupervisor(): void
    {
        $contract = $this->createContract($this->createContractor(), ['supervisor' => null]);

        $response = $this->transition($contract['uuid'], 'active');
        self::assertStatus(422, $response);
        self::assertSame('supervisor', self::json($response)['errors'][0]['field']);

        // Un contratista no es elegible como supervisor.
        $contractorAccount = $this->createUser('otro.contratista@example.test', ['contractor']);
        $invalid = $this->admin->post("/contracts/{$contract['uuid']}/supervisor", ['supervisor' => $contractorAccount]);
        self::assertSame('invalid_supervisor', self::json($invalid)['errors'][0]['code']);

        // Un supervisor desactivado deja de ser elegible.
        self::assertStatus(200, $this->admin->post("/contracts/{$contract['uuid']}/supervisor", ['supervisor' => $this->supervisorUuid]));
        self::assertStatus(200, $this->admin->post("/users/{$this->supervisorUuid}/deactivate"));
        self::assertStatus(422, $this->transition($contract['uuid'], 'active'));

        $candidates = array_column(self::json($this->admin->get('/supervisors'))['data'], 'email');
        self::assertNotContains('supervisor@example.test', $candidates);
    }

    public function testSupervisorCannotBeTheContractorHimself(): void
    {
        // Una persona con ambos perfiles no puede supervisar su propio contrato (conflicto de interés).
        $dual = $this->createUser('doble.perfil@example.test', ['contractor', 'supervisor']);
        $contractor = $this->createContractor(['document_number' => '44444444', 'user' => $dual]);

        $response = $this->admin->post('/contracts', $this->contractBody($contractor, ['supervisor' => $dual]));

        self::assertStatus(422, $response);
        self::assertSame('conflict_of_interest', self::json($response)['errors'][0]['code']);
    }

    public function testSupervisorChangeOnActiveContractRequiresReasonAndIsRecorded(): void
    {
        $contract = $this->createContract($this->createContractor());
        $this->transition($contract['uuid'], 'active');
        $newSupervisor = $this->createUser('nuevo.supervisor@example.test', ['supervisor']);

        self::assertStatus(422, $this->admin->post("/contracts/{$contract['uuid']}/supervisor", ['supervisor' => $newSupervisor]));
        $changed = $this->admin->post("/contracts/{$contract['uuid']}/supervisor", ['supervisor' => $newSupervisor, 'comment' => 'Resolución ficticia de designación.']);

        self::assertStatus(200, $changed);
        self::assertSame($newSupervisor, self::json($changed)['data']['supervisor']['uuid']);
        self::assertStringStartsWith('Supervisor designado', self::json($this->admin->get("/contracts/{$contract['uuid']}/history"))['data'][0]['summary']);
    }

    public function testVisibilityIsScopedByProfile(): void
    {
        $account = $this->createUser('carla@example.test', ['contractor']);
        $carla = $this->createContractor(['document_number' => '10101010', 'user' => $account]);
        $draft = $this->createContract($carla);
        $active = $this->createContract($carla);
        $this->transition($active['uuid'], 'active');

        $otherSupervisor = $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $foreign = $this->createContract($this->createContractor(), ['supervisor' => $otherSupervisor]);
        $this->transition($foreign['uuid'], 'active');

        // Supervisor: solo los que supervisa (incluye borradores asignados).
        $supervisor = $this->browser();
        $supervisor->login('supervisor@example.test');
        $seen = array_column(self::json($supervisor->get('/contracts'))['data'], 'uuid');
        self::assertEqualsCanonicalizing([$draft['uuid'], $active['uuid']], $seen);
        self::assertStatus(404, $supervisor->get("/contracts/{$foreign['uuid']}"), 'Fuera de alcance = 404, no revela existencia');
        self::assertStatus(403, $this->transition($active['uuid'], 'suspended', 'x', $supervisor), 'Supervisar no es administrar');
        // El supervisor consulta a SUS contratistas, no al registro completo (ADR-021).
        $supervisorContractors = self::json($supervisor->get('/contractors'))['data'];
        self::assertSame(['Contratista Ficticio'], array_column($supervisorContractors, 'name'));
        self::assertStatus(403, $supervisor->post('/contractors', ['name' => 'Intento']), 'No crea contratistas');
        self::assertFalse(self::json($supervisor->get("/contracts/{$active['uuid']}"))['data']['actions']['edit']);

        // Contratista: solo sus contratos, sin borradores.
        $contractorBrowser = $this->browser();
        $contractorBrowser->login('carla@example.test');
        self::assertSame([$active['uuid']], array_column(self::json($contractorBrowser->get('/contracts'))['data'], 'uuid'));
        self::assertStatus(404, $contractorBrowser->get("/contracts/{$draft['uuid']}"));
        self::assertStatus(404, $contractorBrowser->get("/contracts/{$foreign['uuid']}/history"));
        self::assertStatus(403, $contractorBrowser->post('/contracts', $this->contractBody($carla)));

        // Administrador: todos.
        self::assertSame(3, self::json($this->admin->get('/contracts'))['meta']['pagination']['total']);
    }

    public function testListFiltersAndDraftDiscard(): void
    {
        $contractor = $this->createContractor(['name' => 'Laura Filtro']);
        $contract = $this->createContract($contractor, ['contract_number' => 'CPS-777-2026', 'end_date' => '2026-03-01']);
        $this->createContract($this->createContractor());

        self::assertSame(['CPS-777-2026'], array_column(self::json($this->admin->get('/contracts?search=Filtro'))['data'], 'contract_number'));
        self::assertSame(1, self::json($this->admin->get('/contracts?ending_before=2026-04-01'))['meta']['pagination']['total']);
        self::assertSame(2, self::json($this->admin->get("/contracts?department={$this->departmentUuid}&status=draft"))['meta']['pagination']['total']);
        self::assertStatus(422, $this->admin->get('/contracts?status=inventado'));

        self::assertStatus(200, $this->admin->delete("/contracts/{$contract['uuid']}"));
        self::assertStatus(404, $this->admin->get("/contracts/{$contract['uuid']}"));
        $reuse = $this->admin->post('/contracts', $this->contractBody($contractor, ['contract_number' => 'CPS-777-2026']));
        self::assertStatus(422, $reuse, 'El número de un borrador descartado no se reutiliza');
        self::assertContains('contract.deleted', $this->auditActions());
    }

    public function testInactiveContractorCannotReceiveNewContracts(): void
    {
        $contractor = $this->createContractor();
        $active = $this->createContract($contractor);
        $this->transition($active['uuid'], 'active');

        $deactivated = $this->admin->post("/contractors/{$contractor}/deactivate");
        self::assertStatus(200, $deactivated);
        self::assertStringContainsString('1 contrato(s) en ejecución', self::json($deactivated)['message']);

        $response = $this->admin->post('/contracts', $this->contractBody($contractor));
        self::assertSame('contractor', self::json($response)['errors'][0]['field']);
        self::assertSame('active', self::json($this->admin->get("/contracts/{$active['uuid']}"))['data']['status'], 'Los contratos en ejecución no se alteran');
    }

    public function testUsersCanBeAssignedToDepartments(): void
    {
        $response = $this->admin->patch("/users/{$this->supervisorUuid}", ['department' => $this->departmentUuid]);

        self::assertStatus(200, $response);
        self::assertSame('SPLAN', self::json($response)['data']['department']['code']);
        self::assertSame(1, self::json($this->admin->get("/users?department={$this->departmentUuid}"))['meta']['pagination']['total']);
        self::assertNull(self::json($this->admin->patch("/users/{$this->supervisorUuid}", ['department' => null]))['data']['department']);
    }

    public function testCheckConstraintsBackTheApplicationRules(): void
    {
        $this->expectException(\PDOException::class);
        // Aunque alguien omita el servicio, la base de datos impide un contrato activo sin supervisor.
        $contract = $this->createContract($this->createContractor());
        $this->pdo()->prepare("UPDATE contracts SET status = 'active', supervisor_user_id = NULL WHERE uuid = ?")->execute([$contract['uuid']]);
    }
}
