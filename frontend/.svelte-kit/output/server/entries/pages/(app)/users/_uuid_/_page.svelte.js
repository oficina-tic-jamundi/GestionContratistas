import { C as attr, T as escape_html, o as derived } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { i as invalidateAll } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as Badge } from "../../../../../chunks/Badge.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { i as formatDateTime } from "../../../../../chunks/format.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { a as setUserActive, i as resetUserPassword, o as updateUser } from "../../../../../chunks/api10.js";
import { n as SecretReveal, t as UserForm } from "../../../../../chunks/UserForm.js";
//#region src/routes/(app)/users/[uuid]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const user = derived(() => data.user);
		const isSelf = derived(() => session.user?.uuid === user().uuid);
		const canEdit = derived(() => session.can(Permission.UsersUpdate));
		const canAssign = derived(() => data.roles.length > 0 && !isSelf());
		const canToggle = derived(() => session.can(Permission.UsersDeactivate) && !isSelf());
		const canReset = derived(() => session.can(Permission.UsersResetPassword) && !isSelf());
		let saving = false;
		let saveError = null;
		let actionError = null;
		let temporaryPassword = null;
		let confirmToggle = false;
		let confirmReset = false;
		let working = false;
		async function save(input) {
			saving = true;
			saveError = null;
			try {
				const changes = {};
				if (canEdit()) Object.assign(changes, {
					first_name: input.first_name,
					last_name: input.last_name,
					email: input.email,
					phone: input.phone,
					department: input.department ?? null
				});
				if (canAssign()) changes.roles = input.roles;
				await updateUser(user().uuid, changes);
				toasts.show("Cambios guardados.");
				await invalidateAll();
			} catch (e) {
				saveError = e;
			} finally {
				saving = false;
			}
		}
		async function toggleActive() {
			working = true;
			actionError = null;
			try {
				await setUserActive(user().uuid, user().status !== "active");
				toasts.show(user().status === "active" ? "Usuario desactivado." : "Usuario activado.");
				await invalidateAll();
			} catch (e) {
				actionError = e;
			} finally {
				working = false;
				confirmToggle = false;
			}
		}
		async function resetPassword() {
			working = true;
			actionError = null;
			try {
				temporaryPassword = (await resetUserPassword(user().uuid)).temporary_password;
				await invalidateAll();
			} catch (e) {
				actionError = e;
			} finally {
				working = false;
				confirmReset = false;
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					Button($$renderer, {
						href: resolve("/users"),
						variant: "secondary",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Volver`);
						},
						$$slots: { default: true }
					});
				}
				PageHeader($$renderer, {
					title: user().full_name,
					description: user().email,
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <div class="grid gap-6 lg:grid-cols-3"><div class="space-y-4 lg:col-span-2">`);
			if (temporaryPassword) {
				$$renderer.push("<!--[0-->");
				SecretReveal($$renderer, {
					secret: temporaryPassword,
					email: user().email
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (formMessage(saveError)) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(formMessage(saveError))}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			Card($$renderer, {
				title: "Datos del usuario",
				children: ($$renderer) => {
					if (canEdit() || canAssign()) {
						$$renderer.push(`<!--[0--><!---->`);
						UserForm($$renderer, {
							initial: {
								first_name: user().first_name,
								last_name: user().last_name,
								email: user().email,
								phone: user().phone,
								roles: user().roles.map((r) => r.code),
								department: user().department?.uuid ?? null
							},
							roles: canAssign() ? data.roles : [],
							departments: data.departments,
							editableFields: canEdit(),
							submitting: saving,
							error: saveError,
							submitLabel: "Guardar cambios",
							onsubmit: save
						});
						$$renderer.push(`<!----> `);
						if (isSelf()) $$renderer.push(`<!--[0--><p class="mt-4 text-xs text-muted">Sus propios roles solo pueden ser modificados por otro administrador.</p>`);
						else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]-->`);
					} else $$renderer.push(`<!--[-1--><dl class="grid gap-3 text-sm sm:grid-cols-2"><div><dt class="text-muted">Nombres</dt> <dd>${escape_html(user().first_name)}</dd></div> <div><dt class="text-muted">Apellidos</dt> <dd>${escape_html(user().last_name)}</dd></div> <div><dt class="text-muted">Correo</dt> <dd>${escape_html(user().email)}</dd></div> <div><dt class="text-muted">Teléfono</dt> <dd>${escape_html(user().phone ?? "—")}</dd></div> <div><dt class="text-muted">Dependencia</dt> <dd>${escape_html(user().department?.name ?? "—")}</dd></div></dl>`);
					$$renderer.push(`<!--]-->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></div> <div class="space-y-4">`);
			Card($$renderer, {
				title: "Estado de la cuenta",
				children: ($$renderer) => {
					$$renderer.push(`<dl class="space-y-2 text-sm"><div class="flex justify-between"><dt class="text-muted">Estado</dt> <dd>`);
					Badge($$renderer, {
						tone: user().status === "active" ? "success" : "neutral",
						children: ($$renderer) => {
							$$renderer.push(`<!---->${escape_html(user().status_label)}`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!----></dd></div> <div class="flex justify-between gap-2"><dt class="text-muted">Roles</dt> <dd class="text-right">${escape_html(user().roles.map((r) => r.name).join(", ") || "—")}</dd></div> <div class="flex justify-between"><dt class="text-muted">Contraseña temporal</dt> <dd>${escape_html(user().must_change_password ? "Sí, pendiente de cambio" : "No")}</dd></div> <div class="flex justify-between"><dt class="text-muted">Último acceso</dt> <dd>${escape_html(formatDateTime(user().last_login_at, "Nunca"))}</dd></div> <div class="flex justify-between"><dt class="text-muted">Creado</dt> <dd>${escape_html(formatDateTime(user().created_at))}</dd></div></dl>`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			if (canToggle() || canReset()) {
				$$renderer.push("<!--[0-->");
				Card($$renderer, {
					title: "Acciones",
					children: ($$renderer) => {
						$$renderer.push(`<div class="space-y-3">`);
						if (formMessage(actionError)) {
							$$renderer.push("<!--[0-->");
							Alert($$renderer, {
								variant: "danger",
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(formMessage(actionError))}`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (canToggle()) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: user().status === "active" ? "danger" : "secondary",
								class: "w-full",
								onclick: () => confirmToggle = true,
								children: ($$renderer) => {
									$$renderer.push(`<!---->${escape_html(user().status === "active" ? "Desactivar usuario" : "Activar usuario")}`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--> `);
						if (canReset()) {
							$$renderer.push("<!--[0-->");
							Button($$renderer, {
								variant: "secondary",
								class: "w-full",
								onclick: () => confirmReset = true,
								children: ($$renderer) => {
									$$renderer.push(`<!---->Restablecer contraseña`);
								},
								$$slots: { default: true }
							});
						} else $$renderer.push("<!--[-1-->");
						$$renderer.push(`<!--]--></div>`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (session.can(Permission.AuditView)) $$renderer.push(`<!--[0--><a${attr("href", resolve(`/audit?entity_type=user&entity_id=${user().uuid}`))} class="block text-sm text-primary hover:underline">Ver historial de auditoría de este usuario</a>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div></div> `);
			ConfirmDialog($$renderer, {
				title: user().status === "active" ? "Desactivar usuario" : "Activar usuario",
				confirmLabel: user().status === "active" ? "Desactivar" : "Activar",
				variant: user().status === "active" ? "danger" : "primary",
				loading: working,
				onconfirm: toggleActive,
				get open() {
					return confirmToggle;
				},
				set open($$value) {
					confirmToggle = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					if (user().status === "active") $$renderer.push(`<!--[0-->${escape_html(user().full_name)} no podrá iniciar sesión y sus sesiones abiertas se cerrarán de inmediato. La acción
    queda registrada en la auditoría.`);
					else $$renderer.push(`<!--[-1-->${escape_html(user().full_name)} podrá volver a iniciar sesión con su contraseña actual.`);
					$$renderer.push(`<!--]-->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> `);
			ConfirmDialog($$renderer, {
				title: "Restablecer contraseña",
				confirmLabel: "Restablecer",
				loading: working,
				onconfirm: resetPassword,
				get open() {
					return confirmReset;
				},
				set open($$value) {
					confirmReset = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<!---->Se generará una contraseña temporal, se cerrarán las sesiones abiertas de ${escape_html(user().full_name)} y deberá
  cambiarla en su próximo inicio de sesión.`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!---->`);
		}
		do {
			$$settled = true;
			$$inner_renderer = $$renderer.copy();
			$$render_inner($$inner_renderer);
		} while (!$$settled);
		$$renderer.subsume($$inner_renderer);
	});
}
//#endregion
export { _page as default };
