import { C as attr, T as escape_html, s as ensure_array_like } from "../../../../chunks/server.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { t as Permission } from "../../../../chunks/permissions.js";
import { t as Badge } from "../../../../chunks/Badge.js";
import { t as session } from "../../../../chunks/session.svelte.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as Tabs } from "../../../../chunks/Tabs.js";
//#region src/routes/(app)/roles/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		{
			function actions($$renderer) {
				if (session.can(Permission.RolesManage)) {
					$$renderer.push("<!--[0-->");
					Button($$renderer, {
						href: resolve("/roles/new"),
						children: ($$renderer) => {
							$$renderer.push(`<!---->Nuevo rol`);
						},
						$$slots: { default: true }
					});
				} else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]-->`);
			}
			PageHeader($$renderer, {
				title: "Roles y permisos",
				description: "Cada rol agrupa permisos. Un usuario puede tener varios roles; sus permisos se suman.",
				actions,
				$$slots: { actions: true }
			});
		}
		$$renderer.push(`<!----> `);
		Tabs($$renderer, {
			label: "Secciones de roles y usuarios",
			items: [{
				href: resolve("/roles"),
				label: "Roles"
			}, {
				href: resolve("/users"),
				label: "Usuarios"
			}]
		});
		$$renderer.push(`<!----> <ul class="grid gap-4 md:grid-cols-2 xl:grid-cols-3"><!--[-->`);
		const each_array = ensure_array_like(data.roles);
		for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
			let role = each_array[$$index];
			$$renderer.push(`<li class="flex flex-col rounded-2xl border border-border bg-surface p-5 shadow-card shadow-xs"><div class="flex items-start justify-between gap-2"><h2 class="font-semibold text-ink"><a${attr("href", resolve("/(app)/roles/[code]", { code: role.code }))} class="hover:text-primary hover:underline">${escape_html(role.name)}</a></h2> `);
			if (role.is_system) {
				$$renderer.push("<!--[0-->");
				Badge($$renderer, {
					children: ($$renderer) => {
						$$renderer.push(`<!---->Sistema`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> <p class="mt-1 font-mono text-xs text-muted">${escape_html(role.code)}</p> `);
			if (role.description) $$renderer.push(`<!--[0--><p class="mt-2 text-sm text-muted">${escape_html(role.description)}</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <p class="mt-auto pt-4 text-sm text-muted">${escape_html(role.permissions.length)} permiso(s) · ${escape_html(role.user_count)} usuario(s)</p></li>`);
		}
		$$renderer.push(`<!--]--></ul>`);
	});
}
//#endregion
export { _page as default };
