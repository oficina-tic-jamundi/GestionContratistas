<?php

declare(strict_types=1);

namespace Sigcon\Tests\Integration;

use PDO;
use Sigcon\Security\Permission;
use Sigcon\Tests\Support\Browser;
use Sigcon\Tests\Support\IntegrationTestCase;

final class RolesAndAuditTest extends IntegrationTestCase
{
    /** Permisos propios de otros perfiles que el administrador NO recibe (ver migración 20260918200400). */
    private const NOT_FOR_ADMIN = [Permission::ContractsSupervise, Permission::ContractsViewAssigned, Permission::ContractsViewOwn, Permission::ActivitiesExecute, Permission::ActivitiesProgress, Permission::ReportsCreate, Permission::ReportsReview, Permission::ReportsApprove, Permission::EvidenceCapture];

    /** @return list<string> */
    private static function adminPermissions(): array
    {
        $codes = array_map(
            static fn (Permission $p) => $p->value,
            array_filter(Permission::cases(), static fn (Permission $p) => !in_array($p, self::NOT_FOR_ADMIN, true)),
        );
        sort($codes);

        return array_values($codes);
    }

    private function admin(): Browser
    {
        $this->createUser('admin@example.test', ['admin']);
        $browser = $this->browser();
        $browser->login('admin@example.test');

        return $browser;
    }

    public function testPermissionEnumMatchesDatabaseCatalog(): void
    {
        $inDatabase = $this->pdo()->query('SELECT code FROM permissions ORDER BY code')?->fetchAll(PDO::FETCH_COLUMN) ?: [];
        $inCode = array_map(static fn (Permission $p) => $p->value, Permission::cases());
        sort($inCode);

        self::assertSame($inCode, $inDatabase);
    }

    public function testCustomRoleGrantsItsPermissions(): void
    {
        $admin = $this->admin();

        $created = $admin->post('/roles', [
            'code' => 'auditor',
            'name' => 'Auditor interno',
            'description' => 'Consulta la auditoría',
            'permissions' => ['audit.view'],
        ]);
        self::assertStatus(201, $created);

        $uuid = $this->createUser('auditora@example.test', ['contractor']);
        self::assertStatus(200, $admin->patch("/users/{$uuid}", ['roles' => ['auditor']]));
        $auditora = $this->browser();
        $auditora->login('auditora@example.test');

        self::assertStatus(200, $auditora->get('/audit-logs'));
        self::assertStatus(403, $auditora->get('/users'));

        // Retirar el permiso aplica de inmediato.
        self::assertStatus(200, $admin->put('/roles/auditor', ['name' => 'Auditor interno', 'permissions' => []]));
        self::assertStatus(403, $auditora->get('/audit-logs'));
    }

    public function testRoleRules(): void
    {
        $admin = $this->admin();

        self::assertStatus(409, $admin->put('/roles/admin', ['name' => 'Administrador', 'permissions' => ['users.view']]));
        self::assertStatus(200, $admin->put('/roles/admin', ['name' => 'Administración', 'permissions' => self::adminPermissions()]));
        self::assertStatus(409, $admin->delete('/roles/supervisor'));
        self::assertStatus(422, $admin->post('/roles', ['code' => 'Mal Código', 'name' => 'X', 'permissions' => []]));
        self::assertStatus(422, $admin->post('/roles', ['code' => 'nuevo', 'name' => 'Nuevo', 'permissions' => ['permiso.inventado']]));

        self::assertStatus(201, $admin->post('/roles', ['code' => 'temporal', 'name' => 'Temporal', 'permissions' => []]));
        self::assertStatus(422, $admin->post('/roles', ['code' => 'temporal', 'name' => 'Duplicado', 'permissions' => []]));
        $uuid = $this->createUser('temp@example.test', ['temporal']);
        self::assertStatus(409, $admin->delete('/roles/temporal'), 'Un rol con usuarios no se elimina');
        self::assertStatus(200, $admin->patch("/users/{$uuid}", ['roles' => []]));
        self::assertStatus(200, $admin->delete('/roles/temporal'));
        self::assertStatus(404, $admin->delete('/roles/temporal'));
    }

    public function testRoleListIncludesPermissionsAndUserCounts(): void
    {
        $admin = $this->admin();

        $roles = array_column(self::json($admin->get('/roles'))['data'], null, 'code');

        self::assertSame(1, $roles['admin']['user_count']);
        self::assertFalse($roles['admin']['permissions_editable']);
        $adminPermissions = $roles['admin']['permissions'];
        sort($adminPermissions);
        self::assertSame(self::adminPermissions(), $adminPermissions);
        self::assertContains('contracts.supervise', $roles['supervisor']['permissions']);
        self::assertSame(['activities.execute', 'contracts.view_own', 'evidence.capture', 'reports.create'], $roles['contractor']['permissions']);
        self::assertContains('reports.approve', $roles['supervisor']['permissions']);
        self::assertCount(count(Permission::cases()), self::json($admin->get('/permissions'))['data']);
    }

    public function testAuditLogCanBeFilteredAndNeverExposesSecrets(): void
    {
        $admin = $this->admin();
        $this->browser()->login('admin@example.test', 'clave-equivocada-000');

        $logins = self::json($admin->get('/audit-logs?action=auth.login'));
        self::assertSame(['auth.login.succeeded', 'auth.login.failed'], array_reverse(array_column($logins['data'], 'action')));
        self::assertSame('Inicio de sesión fallido', $logins['data'][0]['action_label']);
        self::assertSame('203.0.113.10', $logins['data'][0]['ip_address']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{16}$/', $logins['data'][0]['request_id']);

        $all = (string) $admin->get('/audit-logs?per_page=100')->getBody();
        self::assertStringNotContainsString(self::PASSWORD, $all);
        self::assertStringNotContainsString('clave-equivocada', $all);

        self::assertStatus(422, $admin->get('/audit-logs?from=2026-13-01'));
        self::assertStatus(200, $admin->get('/audit-logs?from=2026-01-01&to=2099-12-31'));
        self::assertArrayHasKey('user.created', self::json($admin->get('/audit-logs/actions'))['data']);
    }
}
