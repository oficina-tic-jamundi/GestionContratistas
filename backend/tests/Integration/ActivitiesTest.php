<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use Psr\Http\Message\ResponseInterface;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\IntegrationTestCase;

final class ActivitiesTest extends IntegrationTestCase
{
    private Browser $admin;
    private Browser $contractor;
    private Browser $supervisor;
    private string $contract;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createUser('admin@example.test', ['admin']);
        $supervisorUuid = $this->createUser('supervisor@example.test', ['supervisor']);
        $accountUuid = $this->createUser('contratista@example.test', ['contractor']);
        $this->admin = $this->browser();
        $this->admin->login('admin@example.test');

        $department = self::json($this->admin->post('/departments', ['code' => 'SPLAN', 'name' => 'Planeación']))['data']['uuid'];
        $contractor = self::json($this->admin->post('/contractors', [
            'person_type' => 'natural', 'document_type' => 'CC', 'document_number' => '11111111', 'name' => 'Carla Contratista', 'user' => $accountUuid,
        ]))['data']['uuid'];
        $this->contract = self::json($this->admin->post('/contracts', [
            'contract_number' => 'CPS-001-2026',
            'object' => 'Prestación de servicios profesionales de prueba.',
            'contractor' => $contractor,
            'department' => $department,
            'supervisor' => $supervisorUuid,
            'start_date' => '2026-01-15',
            'end_date' => '2026-12-15',
            'total_value' => '42000000',
        ]))['data']['uuid'];

        $this->contractor = $this->browser();
        $this->contractor->login('contratista@example.test');
        $this->supervisor = $this->browser();
        $this->supervisor->login('supervisor@example.test');
    }

    private function obligation(string $title, string $weight = '1'): string
    {
        $r = $this->admin->post("/contracts/{$this->contract}/obligations", ['title' => $title, 'weight' => $weight]);
        self::assertStatus(201, $r);

        return self::json($r)['data']['uuid'];
    }

    private function child(Browser $as, string $parent, string $title, string $weight = '1'): string
    {
        $r = $as->post("/activities/{$parent}/children", ['title' => $title, 'weight' => $weight]);
        self::assertStatus(201, $r);

        return self::json($r)['data']['uuid'];
    }

    private function progress(Browser $as, string $activity, int $value, string $note = 'Avance verificado según lo planeado.'): ResponseInterface
    {
        return $as->post("/activities/{$activity}/progress", ['progress' => $value, 'note' => $note]);
    }

    private function activate(): void
    {
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'active']));
    }

    /** @return array<string, mixed> */
    private function tree(Browser $as): array
    {
        $r = $as->get("/contracts/{$this->contract}/activities");
        self::assertStatus(200, $r);

        return self::json($r)['data'];
    }

    public function testObligationsAreDefinedByManagersWhileDraft(): void
    {
        $this->obligation('Apoyar la formulación de proyectos');
        self::assertStatus(403, $this->contractor->post("/contracts/{$this->contract}/obligations", ['title' => 'Intento del contratista']));

        $tree = $this->tree($this->admin);
        self::assertTrue($tree['can']['manage_obligations']);
        self::assertSame('0.00', $tree['progress']);

        $this->activate();
        $late = $this->admin->post("/contracts/{$this->contract}/obligations", ['title' => 'Obligación tardía']);
        self::assertStatus(409, $late);
        self::assertStringContainsString('borrador', self::json($late)['message']);
        self::assertFalse($this->tree($this->admin)['can']['manage_obligations']);
    }

    public function testSupervisorPlansTasksAndRecordsWeightedProgress(): void
    {
        $o1 = $this->obligation('Obligación uno', '3');
        $o2 = $this->obligation('Obligación dos', '1');
        $this->activate();

        $t1 = $this->child($this->supervisor, $o1, 'Tarea 1.1');
        $t2 = $this->child($this->supervisor, $o1, 'Tarea 1.2');
        $s1 = $this->child($this->admin, $t2, 'Subtarea 1.2.1');
        $s2 = $this->child($this->admin, $t2, 'Subtarea 1.2.2');

        self::assertStatus(200, $this->progress($this->supervisor, $t1, 100));
        self::assertStatus(200, $this->progress($this->supervisor, $s1, 50));

        // t2 = (50+0)/2 = 25 ; o1 = (100+25)/2 = 62.5 ; o2 = 0 ; contrato = (62.5x3 + 0x1)/4 = 46.88
        $tree = $this->tree($this->supervisor);
        self::assertSame('46.88', $tree['progress']);
        $obligation1 = $tree['items'][0];
        self::assertSame('62.50', $obligation1['progress']);
        self::assertTrue($obligation1['progress_is_computed']);
        self::assertSame('25.00', $obligation1['children'][1]['progress']);
        self::assertSame('Subtarea 1.2.2', $obligation1['children'][1]['children'][1]['title']);
        self::assertTrue($tree['can']['record_progress']);
        self::assertTrue($tree['can']['plan'], 'El supervisor planea también con el contrato en ejecución');

        // Un peso distinto cambia el promedio del padre al instante.
        self::assertStatus(200, $this->supervisor->put("/activities/{$s2}", ['title' => 'Subtarea 1.2.2', 'weight' => '3']));
        self::assertSame('12.50', $this->tree($this->admin)['items'][0]['children'][1]['progress']);
        unset($o2);
    }

    public function testTheContractorOnlyReadsTasks(): void
    {
        $o1 = $this->obligation('Obligación');
        $this->activate();
        $t1 = $this->child($this->supervisor, $o1, 'Tarea del supervisor');
        self::assertStatus(200, $this->progress($this->supervisor, $t1, 30));

        $tree = $this->tree($this->contractor);
        self::assertSame(
            ['manage_obligations' => false, 'plan' => false, 'record_progress' => false, 'set_priority' => false],
            $tree['can'],
            'El contratista no edita nada (ADR-021)',
        );
        self::assertSame('30.00', $tree['items'][0]['children'][0]['progress'], 'Pero sí consulta su avance');

        self::assertStatus(403, $this->contractor->post("/activities/{$o1}/children", ['title' => 'Tarea del contratista']));
        self::assertStatus(403, $this->contractor->put("/activities/{$t1}", ['title' => 'Tarea renombrada']));
        self::assertStatus(403, $this->contractor->delete("/activities/{$t1}"));
        self::assertStatus(403, $this->progress($this->contractor, $t1, 60));
    }

    public function testProgressRulesPreventIncoherence(): void
    {
        $o1 = $this->obligation('Obligación con tareas');
        $this->activate();
        $t1 = $this->child($this->supervisor, $o1, 'Tarea con avance');

        // Los nodos con hijos no reciben avance declarado: se calcula.
        $onParent = $this->progress($this->supervisor, $o1, 80);
        self::assertStatus(409, $onParent);
        self::assertStringContainsString('se calcula', self::json($onParent)['message']);

        // Validación: rango y nota obligatoria.
        self::assertStatus(422, $this->progress($this->supervisor, $t1, 120));
        self::assertStatus(422, $this->progress($this->supervisor, $t1, 40, 'corta'));

        self::assertStatus(200, $this->progress($this->supervisor, $t1, 40));
        // No se divide un elemento que ya tiene avance registrado.
        self::assertStatus(409, $this->supervisor->post("/activities/{$t1}/children", ['title' => 'Subtarea tardía']));
        // No se elimina un elemento con historial de avances.
        self::assertStatus(409, $this->supervisor->delete("/activities/{$t1}"));
        // No se elimina un nodo con hijos.
        self::assertStatus(409, $this->admin->delete("/activities/{$o1}"));
        // Las subtareas no admiten más niveles.
        $t2 = $this->child($this->supervisor, $o1, 'Tarea dos');
        $sub = $this->child($this->supervisor, $t2, 'Subtarea');
        self::assertStatus(409, $this->supervisor->post("/activities/{$sub}/children", ['title' => 'Nivel cuatro']));

        // Las correcciones a la baja se permiten y quedan con su nota.
        self::assertStatus(200, $this->progress($this->supervisor, $t1, 30, 'Corrección: parte del trabajo fue devuelta.'));
        $history = self::json($this->contractor->get("/activities/{$t1}/progress"))['data']['history'];
        self::assertSame(['40.00', '30.00'], [$history[1]['new_progress'], $history[0]['new_progress']]);
        self::assertSame('40.00', $history[0]['previous_progress']);
        self::assertSame('Prueba Supervisor', $history[0]['user'], 'El avance lo registra el supervisor');
    }

    public function testOnlyTheSupervisorOfTheContractRecordsProgress(): void
    {
        $o1 = $this->obligation('Obligación');
        $this->activate();
        $t1 = $this->child($this->admin, $o1, 'Tarea creada por el gestor');

        self::assertStatus(403, $this->progress($this->admin, $t1, 50), 'La administración planea, pero no verifica el avance');

        // Otro supervisor no toca un contrato que no supervisa: ni siquiera lo ve.
        $this->createUser('otro.supervisor@example.test', ['supervisor']);
        $otherSupervisor = $this->browser();
        $otherSupervisor->login('otro.supervisor@example.test');
        self::assertStatus(404, $this->progress($otherSupervisor, $t1, 50));
        self::assertStatus(404, $otherSupervisor->get("/contracts/{$this->contract}/activities"));

        // Ningún contratista planea ni registra avance: se rechaza por permiso.
        $this->createUser('otro@example.test', ['contractor']);
        $other = $this->browser();
        $other->login('otro@example.test');
        self::assertStatus(403, $this->progress($other, $t1, 50));
        self::assertStatus(403, $other->post("/activities/{$o1}/children", ['title' => 'Intrusión']));
        self::assertStatus(404, $other->get("/contracts/{$this->contract}/activities"), 'Y tampoco ve el contrato ajeno');

        self::assertStatus(200, $this->progress($this->supervisor, $t1, 50));
    }

    public function testNothingChangesOutsideAnActiveContract(): void
    {
        $o1 = $this->obligation('Obligación');
        $this->activate();
        $t1 = $this->child($this->supervisor, $o1, 'Tarea');
        self::assertStatus(200, $this->admin->post("/contracts/{$this->contract}/transitions", ['status' => 'suspended', 'comment' => 'Suspensión de prueba.']));

        self::assertStatus(409, $this->progress($this->supervisor, $t1, 10));
        self::assertStatus(409, $this->supervisor->post("/activities/{$o1}/children", ['title' => 'Nueva tarea']));
        self::assertFalse($this->tree($this->supervisor)['can']['record_progress']);
    }

    public function testEveryChangeIsAudited(): void
    {
        $o1 = $this->obligation('Obligación');
        $this->activate();
        $t1 = $this->child($this->supervisor, $o1, 'Tarea');
        $this->progress($this->supervisor, $t1, 20);

        $actions = $this->auditActions();
        self::assertContains('activity.created', $actions);
        self::assertContains('activity.progress_recorded', $actions);
    }
}
