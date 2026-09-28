import { T as escape_html, d as stringify, o as derived } from "../../../../../chunks/server.js";
import { t as toasts } from "../../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../../chunks/paths.js";
import { i as invalidateAll, n as goto } from "../../../../../chunks/client.js";
import "../../../../../chunks/navigation.js";
import { t as Permission } from "../../../../../chunks/permissions.js";
import { t as session } from "../../../../../chunks/session.svelte.js";
import { t as Button } from "../../../../../chunks/Button.js";
import { t as Card } from "../../../../../chunks/Card.js";
import { t as Alert } from "../../../../../chunks/Alert.js";
import { t as PageHeader } from "../../../../../chunks/PageHeader.js";
import { n as formMessage } from "../../../../../chunks/forms.js";
import { t as ConfirmDialog } from "../../../../../chunks/ConfirmDialog.js";
import { a as updateRole, n as deleteRole } from "../../../../../chunks/api13.js";
import { t as RoleForm } from "../../../../../chunks/RoleForm.js";
//#region src/routes/(app)/roles/[code]/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		const role = derived(() => data.role);
		const canManage = derived(() => session.can(Permission.RolesManage));
		let submitting = false;
		let error = null;
		let confirmDelete = false;
		async function save(input) {
			submitting = true;
			error = null;
			try {
				await updateRole(role().code, input);
				toasts.show("Rol actualizado. Los cambios aplican de inmediato a sus usuarios.");
				await invalidateAll();
			} catch (e) {
				error = e;
			} finally {
				submitting = false;
			}
		}
		async function remove() {
			submitting = true;
			error = null;
			try {
				await deleteRole(role().code);
				toasts.show("Rol eliminado.");
				await goto(resolve("/roles"));
			} catch (e) {
				error = e;
				confirmDelete = false;
			} finally {
				submitting = false;
			}
		}
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			{
				function actions($$renderer) {
					if (canManage() && !role().is_system) {
						$$renderer.push("<!--[0-->");
						Button($$renderer, {
							variant: "danger",
							onclick: () => confirmDelete = true,
							disabled: role().user_count > 0,
							title: role().user_count > 0 ? "Retire el rol de sus usuarios antes de eliminarlo" : void 0,
							children: ($$renderer) => {
								$$renderer.push(`<!---->Eliminar rol`);
							},
							$$slots: { default: true }
						});
					} else $$renderer.push("<!--[-1-->");
					$$renderer.push(`<!--]--> `);
					Button($$renderer, {
						href: resolve("/roles"),
						variant: "secondary",
						children: ($$renderer) => {
							$$renderer.push(`<!---->Volver`);
						},
						$$slots: { default: true }
					});
					$$renderer.push(`<!---->`);
				}
				PageHeader($$renderer, {
					title: role().name,
					description: `Código: ${stringify(role().code)}`,
					actions,
					$$slots: { actions: true }
				});
			}
			$$renderer.push(`<!----> <div class="max-w-4xl space-y-4">`);
			if (formMessage(error)) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(formMessage(error))}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			Card($$renderer, {
				children: ($$renderer) => {
					$$renderer.push(`<!---->`);
					RoleForm($$renderer, {
						initial: {
							name: role().name,
							description: role().description,
							permissions: role().permissions
						},
						permissions: data.permissions,
						isNew: false,
						permissionsEditable: role().permissions_editable,
						readonly: !canManage(),
						submitting,
						error,
						onsubmit: save
					});
					$$renderer.push(`<!---->`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----> <p class="text-sm text-muted">Usuarios con este rol: ${escape_html(role().user_count)}</p></div> `);
			ConfirmDialog($$renderer, {
				title: "Eliminar rol",
				confirmLabel: "Eliminar",
				variant: "danger",
				loading: submitting,
				onconfirm: remove,
				get open() {
					return confirmDelete;
				},
				set open($$value) {
					confirmDelete = $$value;
					$$settled = false;
				},
				children: ($$renderer) => {
					$$renderer.push(`<!---->El rol "${escape_html(role().name)}" se eliminará de forma permanente. La acción queda registrada en la auditoría.`);
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
