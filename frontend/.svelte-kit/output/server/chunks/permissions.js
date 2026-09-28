//#region src/lib/auth/permissions.ts
/**
* Catálogo de permisos. Espejo de backend/src/Security/Permission.php.
*
* En el frontend los permisos SOLO deciden qué mostrar (usabilidad).
* La autorización real la hace siempre el backend.
*/
var Permission = {
	UsersView: "users.view",
	UsersCreate: "users.create",
	UsersUpdate: "users.update",
	UsersDeactivate: "users.deactivate",
	UsersResetPassword: "users.reset_password",
	UsersAssignRoles: "users.assign_roles",
	RolesView: "roles.view",
	RolesManage: "roles.manage",
	AuditView: "audit.view",
	DepartmentsView: "departments.view",
	DepartmentsManage: "departments.manage",
	ContractorsView: "contractors.view",
	ContractorsManage: "contractors.manage",
	ContractsViewAll: "contracts.view_all",
	ContractsViewAssigned: "contracts.view_assigned",
	ContractsViewOwn: "contracts.view_own",
	ContractsManage: "contracts.manage",
	ContractsSupervise: "contracts.supervise",
	ActivitiesExecute: "activities.execute",
	EvidenceCapture: "evidence.capture",
	EvidenceViewOriginal: "evidence.view_original",
	JobsManage: "jobs.manage",
	PaymentsManage: "payments.manage",
	PaymentsApprove: "payments.approve",
	PaymentsRegister: "payments.register",
	PaymentsConfigure: "payments.configure",
	ExportsRun: "exports.run",
	ReportsCreate: "reports.create",
	ReportsReview: "reports.review",
	ReportsApprove: "reports.approve",
	ReportsReopen: "reports.reopen"
};
//#endregion
export { Permission as t };
