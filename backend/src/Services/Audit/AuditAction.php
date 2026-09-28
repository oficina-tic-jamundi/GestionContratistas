<?php

declare(strict_types=1);

namespace Sigcon\Services\Audit;

/**
 * Catálogo central de eventos auditables. Formato: <entidad>.<evento>.
 * Los módulos posteriores agregan sus eventos aquí.
 */
enum AuditAction: string
{
    case LoginSucceeded = 'auth.login.succeeded';
    case LoginFailed = 'auth.login.failed';
    case LoginBlocked = 'auth.login.blocked';
    case Logout = 'auth.logout';
    case PasswordChanged = 'auth.password.changed';

    case UserCreated = 'user.created';
    case UserUpdated = 'user.updated';
    case UserActivated = 'user.activated';
    case UserDeactivated = 'user.deactivated';
    case UserPasswordReset = 'user.password_reset';
    case UserRolesChanged = 'user.roles_changed';

    case RoleCreated = 'role.created';
    case RoleUpdated = 'role.updated';
    case RoleDeleted = 'role.deleted';

    case DepartmentCreated = 'department.created';
    case DepartmentUpdated = 'department.updated';
    case DepartmentActivated = 'department.activated';
    case DepartmentDeactivated = 'department.deactivated';

    case ContractorCreated = 'contractor.created';
    case ContractorUpdated = 'contractor.updated';
    case ContractorActivated = 'contractor.activated';
    case ContractorDeactivated = 'contractor.deactivated';

    case ContractCreated = 'contract.created';
    case ContractUpdated = 'contract.updated';
    case ContractStatusChanged = 'contract.status_changed';
    case ContractSupervisorChanged = 'contract.supervisor_changed';
    case ContractDeleted = 'contract.deleted';

    case ActivityCreated = 'activity.created';
    case ActivityUpdated = 'activity.updated';
    case ActivityDeleted = 'activity.deleted';
    case ActivityProgressRecorded = 'activity.progress_recorded';
    case ActivityPriorityChanged = 'activity.priority_changed';

    case DocumentUploaded = 'document.uploaded';
    case DocumentDownloaded = 'document.downloaded';
    case DocumentWithdrawn = 'document.withdrawn';

    case ReportCreated = 'report.created';
    case ReportSubmitted = 'report.submitted';
    case ReportReviewStarted = 'report.review_started';
    case ReportObserved = 'report.observed';
    case ReportApproved = 'report.approved';
    case ReportRejected = 'report.rejected';
    case ReportReopened = 'report.reopened';
    case ReportPdfDownloaded = 'report.pdf_downloaded';

    case JobRetried = 'job.retried';

    case PaymentCreated = 'payment.created';
    case PaymentUpdated = 'payment.updated';
    case PaymentEvaluated = 'payment.evaluated';
    case PaymentSubmitted = 'payment.submitted';
    case PaymentApproved = 'payment.approved';
    case PaymentReturned = 'payment.returned';
    case PaymentPaid = 'payment.paid';
    case PaymentCancelled = 'payment.cancelled';
    case PaymentRuleConfigured = 'payment.rule_configured';

    case ExportGenerated = 'export.generated';

    case AiRequested = 'ai.requested';

    case EvidenceCaptured = 'evidence.captured';
    case EvidenceWithdrawn = 'evidence.withdrawn';
    case EvidenceOriginalViewed = 'evidence.original_viewed';

    public function label(): string
    {
        return match ($this) {
            self::LoginSucceeded => 'Inicio de sesión',
            self::LoginFailed => 'Inicio de sesión fallido',
            self::LoginBlocked => 'Inicio de sesión bloqueado',
            self::Logout => 'Cierre de sesión',
            self::PasswordChanged => 'Cambio de contraseña',
            self::UserCreated => 'Usuario creado',
            self::UserUpdated => 'Usuario modificado',
            self::UserActivated => 'Usuario activado',
            self::UserDeactivated => 'Usuario desactivado',
            self::UserPasswordReset => 'Contraseña restablecida',
            self::UserRolesChanged => 'Roles modificados',
            self::RoleCreated => 'Rol creado',
            self::RoleUpdated => 'Rol modificado',
            self::RoleDeleted => 'Rol eliminado',
            self::DepartmentCreated => 'Dependencia creada',
            self::DepartmentUpdated => 'Dependencia modificada',
            self::DepartmentActivated => 'Dependencia activada',
            self::DepartmentDeactivated => 'Dependencia desactivada',
            self::ContractorCreated => 'Contratista creado',
            self::ContractorUpdated => 'Contratista modificado',
            self::ContractorActivated => 'Contratista activado',
            self::ContractorDeactivated => 'Contratista desactivado',
            self::ContractCreated => 'Contrato creado',
            self::ContractUpdated => 'Contrato modificado',
            self::ContractStatusChanged => 'Cambio de estado de contrato',
            self::ContractSupervisorChanged => 'Cambio de supervisor',
            self::ContractDeleted => 'Borrador de contrato descartado',
            self::ActivityCreated => 'Obligación o tarea creada',
            self::ActivityUpdated => 'Obligación o tarea modificada',
            self::ActivityDeleted => 'Obligación o tarea eliminada',
            self::ActivityProgressRecorded => 'Avance registrado',
            self::ActivityPriorityChanged => 'Prioridad asignada',
            self::DocumentUploaded => 'Documento cargado',
            self::DocumentDownloaded => 'Documento descargado',
            self::DocumentWithdrawn => 'Documento retirado',
            self::ReportCreated => 'Informe creado',
            self::ReportSubmitted => 'Informe enviado',
            self::ReportReviewStarted => 'Revisión de informe iniciada',
            self::ReportObserved => 'Informe con observaciones',
            self::ReportApproved => 'Informe aprobado',
            self::ReportRejected => 'Informe rechazado',
            self::ReportReopened => 'Informe reabierto',
            self::ReportPdfDownloaded => 'PDF de informe descargado',
            self::JobRetried => 'Tarea en segundo plano reintentada',
            self::PaymentCreated => 'Pago registrado',
            self::PaymentUpdated => 'Pago modificado',
            self::PaymentEvaluated => 'Elegibilidad de pago evaluada',
            self::PaymentSubmitted => 'Pago enviado a aprobación',
            self::PaymentApproved => 'Pago aprobado',
            self::PaymentReturned => 'Pago devuelto',
            self::PaymentPaid => 'Pago registrado como pagado',
            self::PaymentCancelled => 'Pago anulado',
            self::PaymentRuleConfigured => 'Regla de elegibilidad configurada',
            self::ExportGenerated => 'Reporte exportado',
            self::AiRequested => 'Consulta a inteligencia artificial',
            self::EvidenceCaptured => 'Evidencia fotográfica registrada',
            self::EvidenceWithdrawn => 'Evidencia fotográfica retirada',
            self::EvidenceOriginalViewed => 'Fotografía original consultada',
        };
    }
}
