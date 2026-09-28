import { C as attr, T as escape_html, n as attr_style, o as derived, s as ensure_array_like, t as attr_class } from "../../../chunks/server.js";
import { t as resolve } from "../../../chunks/paths.js";
import { t as afterNavigate } from "../../../chunks/client.js";
import { t as page } from "../../../chunks/state.js";
import "../../../chunks/navigation.js";
import { t as Permission } from "../../../chunks/permissions.js";
import "../../../chunks/Badge.js";
import { t as Icon } from "../../../chunks/Icon.js";
import { t as BrandMark } from "../../../chunks/BrandMark.js";
import { t as session } from "../../../chunks/session.svelte.js";
import { t as unread } from "../../../chunks/unread.svelte.js";
import "../../../chunks/format.js";
//#region src/lib/features/notifications/NotificationBell.svelte
function NotificationBell($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		const label = derived(() => unread.count === 0 ? "Notificaciones" : `Notificaciones: ${unread.count} sin leer`);
		$$renderer.push(`<a${attr("href", resolve("/notifications"))} class="relative inline-flex size-10 items-center justify-center rounded-xl text-muted transition hover:bg-primary-soft hover:text-ink"${attr("aria-label", label())}${attr("title", label())}><svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg> `);
		if (unread.count > 0) $$renderer.push(`<!--[0--><span class="absolute -top-1 -right-1 min-w-5 rounded-full bg-danger-strong px-1 text-center text-[11px] leading-5 font-semibold text-white" aria-hidden="true">${escape_html(unread.count > 99 ? "99+" : unread.count)}</span>`);
		else $$renderer.push("<!--[-1-->");
		$$renderer.push(`<!--]--></a>`);
	});
}
//#endregion
//#region src/lib/components/layout/AppShell.svelte
function AppShell($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { children } = $$props;
		const canSeeContracts = derived(() => session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned, Permission.ContractsViewOwn));
		/**
		* Un menú corto por rol (ADR-021): cada persona ve solo lo que usa.
		*
		* - Contratista: su panel y sus actividades.
		* - Supervisor: su panel y sus contratistas (allí están las actividades y los informes).
		* - Administración: panel, contratistas, historial, estadísticas y roles y permisos.
		*
		* Lo demás (contratos, pagos, dependencias, auditoría, tareas programadas) sigue disponible
		* desde las pantallas donde se necesita; no ocupa espacio en el menú.
		*/
		const isContractor = derived(() => session.can(Permission.ActivitiesExecute) && !session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned));
		const isSupervisor = derived(() => session.can(Permission.ContractsViewAssigned) && !session.can(Permission.ContractsViewAll));
		const mainNav = derived(() => {
			if (isContractor()) return [{
				path: "/",
				label: "Panel de control",
				icon: "grid",
				visible: true
			}, {
				path: "/activities",
				label: "Mis actividades",
				icon: "check-list",
				visible: true
			}];
			if (isSupervisor()) return [{
				path: "/",
				label: "Panel de control",
				icon: "grid",
				visible: true
			}, {
				path: "/contractors",
				label: "Contratistas",
				icon: "users",
				visible: true
			}];
			return [
				{
					path: "/",
					label: "Panel de control",
					icon: "grid",
					visible: true
				},
				{
					path: "/contractors",
					label: "Contratistas",
					icon: "users",
					visible: session.can(Permission.ContractorsView)
				},
				{
					path: "/history",
					label: "Historial",
					icon: "clock",
					visible: canSeeContracts()
				},
				{
					path: "/statistics",
					label: "Estadísticas",
					icon: "chart",
					visible: session.canAny(Permission.ContractsViewAll, Permission.ContractsViewAssigned)
				},
				{
					path: "/roles",
					label: "Roles y permisos",
					icon: "shield",
					visible: session.can(Permission.RolesView)
				}
			].filter((item) => item.visible);
		});
		function isActive(path) {
			const href = resolve(path);
			return path === "/" ? page.url.pathname === href : page.url.pathname.startsWith(href);
		}
		const OTHER_TITLES = [
			["/account/password", "Cambiar contraseña"],
			["/contracts", "Contratos"],
			["/reports", "Informes"],
			["/payments", "Pagos"],
			["/departments", "Dependencias"],
			["/users", "Usuarios"],
			["/audit", "Auditoría"],
			["/jobs", "Tareas programadas"],
			["/notifications", "Notificaciones"]
		];
		const pageTitle = derived(() => {
			const active = mainNav().find((item) => isActive(item.path));
			if (active) return active.label;
			const path = page.url.pathname;
			return OTHER_TITLES.find(([route]) => path.startsWith(resolve(route)))?.[1] ?? "SIGCON";
		});
		const today = new Intl.DateTimeFormat("es-CO", {
			day: "numeric",
			month: "short",
			year: "numeric",
			timeZone: "America/Bogota"
		}).format(/* @__PURE__ */ new Date());
		const initial = derived(() => session.user?.full_name.trim().charAt(0).toUpperCase() ?? "?");
		let menuOpen = false;
		let profileOpen = false;
		let loggingOut = false;
		let collapsed = false;
		let navKey = 0;
		let lastPath = page.url.pathname;
		afterNavigate(() => {
			refreshedAt = Date.now();
			if (page.url.pathname !== lastPath) {
				lastPath = page.url.pathname;
				navKey++;
			}
		});
		/**
		* Los datos de la pantalla se vuelven a pedir solos: al volver a la pestaña después de un
		* rato y cada dos minutos mientras se está mirando. Así el tablero no muestra cifras viejas
		* cuando algo cambió en el servidor (otro usuario, el Cron o una limpieza de datos).
		*/
		let refreshing = false;
		let refreshedAt = Date.now();
		function navList($$renderer, rail) {
			$$renderer.push(`<nav aria-label="Principal" class="flex-1 overflow-y-auto px-3 py-5">`);
			if (!rail) $$renderer.push(`<!--[0--><p class="label-eyebrow px-3 pb-2">Menú principal</p>`);
			else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> <ul class="space-y-1"><!--[-->`);
			const each_array = ensure_array_like(mainNav());
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let item = each_array[$$index];
				const current = isActive(item.path);
				$$renderer.push(`<li><a${attr("href", resolve(item.path))}${attr("aria-current", current ? "page" : void 0)}${attr("title", rail ? item.label : void 0)}${attr_class(`group relative flex items-center rounded-xl text-[15px] font-medium transition-colors ${rail ? "justify-center px-0 py-3" : "gap-3 px-3.5 py-3"} ${current ? "bg-primary-soft text-primary" : "text-ink hover:bg-white/5"}`)}>`);
				if (current) $$renderer.push(`<!--[0--><span class="absolute inset-y-2 left-0 w-[3px] rounded-full bg-primary" aria-hidden="true"></span>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> `);
				Icon($$renderer, {
					name: item.icon,
					class: "size-5 shrink-0"
				});
				$$renderer.push(`<!----> `);
				if (rail) $$renderer.push(`<!--[0--><span class="sr-only">${escape_html(item.label)}</span>`);
				else $$renderer.push(`<!--[-1--><span class="truncate">${escape_html(item.label)}</span>`);
				$$renderer.push(`<!--]--></a></li>`);
			}
			$$renderer.push(`<!--]--></ul></nav>`);
		}
		function profileBlock($$renderer, rail) {
			if (session.user) {
				$$renderer.push(`<!--[0--><div class="border-t border-border px-3 py-3"><button type="button"${attr_class(`flex w-full items-center rounded-xl text-left transition-colors hover:bg-white/5 ${rail ? "justify-center px-0 py-2" : "gap-3 px-2 py-2"}`)}${attr("aria-expanded", rail ? void 0 : profileOpen)}${attr("aria-controls", rail ? void 0 : "perfil-cuenta")}${attr("title", rail ? "Mi cuenta" : void 0)}><span class="inline-flex size-10 shrink-0 items-center justify-center rounded-full bg-linear-135 from-[#3b82f6] to-[#2563eb] font-semibold text-white shadow-lg shadow-blue-900/40" aria-hidden="true">${escape_html(initial())}</span> `);
				if (rail) $$renderer.push(`<!--[0--><span class="sr-only">Mi cuenta</span>`);
				else {
					$$renderer.push(`<!--[-1--><span class="min-w-0 flex-1"><span class="block truncate font-semibold text-ink">${escape_html(session.user.full_name)}</span> <span class="block truncate text-xs text-primary">${escape_html(session.user.roles.map((r) => r.name).join(", ") || "Sin rol asignado")}</span></span> `);
					Icon($$renderer, {
						name: "chevron",
						class: `size-4 shrink-0 text-muted transition-transform `
					});
					$$renderer.push(`<!----> <span class="sr-only">Mi cuenta</span>`);
				}
				$$renderer.push(`<!--]--></button> `);
				$$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <div class="mt-1"><button type="button"${attr_class(`flex w-full items-center rounded-lg text-sm text-muted transition-colors hover:bg-white/5 hover:text-ink ${rail ? "justify-center px-0 py-2.5" : "gap-2.5 px-2 py-2"}`)}${attr("title", rail ? "Cerrar sesión" : void 0)}${attr("disabled", loggingOut, true)}>`);
				Icon($$renderer, {
					name: "logout",
					class: "size-4 shrink-0"
				});
				$$renderer.push(`<!----> `);
				if (rail) $$renderer.push(`<!--[0--><span class="sr-only">Cerrar sesión</span>`);
				else $$renderer.push(`<!--[-1-->Cerrar sesión`);
				$$renderer.push(`<!--]--></button></div></div>`);
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]-->`);
		}
		$$renderer.push(`<a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-50 focus:rounded-lg focus:bg-deep focus:px-3 focus:py-2">Saltar al contenido</a> <div class="flex min-h-dvh"><aside${attr_class(`sticky top-0 hidden h-dvh shrink-0 flex-col border-r border-border bg-surface-strong transition-[width] duration-200 ease-out lg:flex w-[17.5rem]`)} aria-label="Menú lateral"><div${attr_class(`flex items-center gap-3 border-b border-border px-4 py-4 `)}>`);
		$$renderer.push(`<!--[0--><a${attr("href", resolve("/"))} class="flex min-w-0 flex-1 items-center gap-3">`);
		BrandMark($$renderer, { size: "md" });
		$$renderer.push(`<!----> <span class="min-w-0"><span class="block truncate font-semibold text-ink">Alcaldía Jamundí</span> <span class="block text-xs text-muted" translate="no">SIGCON</span></span></a>`);
		$$renderer.push(`<!--]--> <button type="button" class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink"${attr("aria-label", "Contraer menú")}${attr("aria-expanded", true)}${attr("title", "Contraer menú")}>`);
		Icon($$renderer, {
			name: "menu",
			class: "size-5"
		});
		$$renderer.push(`<!----></button></div> `);
		navList($$renderer, collapsed);
		$$renderer.push(`<!----> `);
		profileBlock($$renderer, collapsed);
		$$renderer.push(`<!----></aside> <div class="flex min-w-0 flex-1 flex-col"><header class="sticky top-0 z-30 border-b border-border bg-surface-strong"${attr_style("", { "padding-top": "env(safe-area-inset-top)" })}><div class="flex items-center gap-3 px-4 py-3 sm:gap-4 sm:px-6"><button type="button" class="inline-flex size-10 items-center justify-center rounded-xl text-muted transition-colors hover:bg-primary-soft hover:text-ink lg:hidden" aria-label="Abrir menú" aria-haspopup="dialog"${attr("aria-expanded", menuOpen)} aria-controls="menu-principal">`);
		Icon($$renderer, {
			name: "menu",
			class: "size-6"
		});
		$$renderer.push(`<!----></button> <a${attr("href", resolve("/"))} class="flex items-center gap-2.5 lg:hidden">`);
		BrandMark($$renderer, {});
		$$renderer.push(`<!----> <span class="sr-only font-semibold text-ink sm:not-sr-only">Alcaldía Jamundí</span></a> <span class="hidden h-6 w-px bg-border sm:block lg:hidden" aria-hidden="true"></span> <p class="min-w-0 truncate text-base font-semibold text-ink sm:text-lg">${escape_html(pageTitle())}</p> <div class="ml-auto flex items-center gap-1 sm:gap-2"><span class="mr-2 hidden text-sm text-muted md:inline">${escape_html(today)}</span> <button type="button" class="inline-flex size-10 items-center justify-center rounded-xl text-muted transition-colors hover:bg-primary-soft hover:text-ink" aria-label="Actualizar los datos de la pantalla" title="Actualizar los datos de la pantalla"${attr("disabled", refreshing, true)}>`);
		Icon($$renderer, {
			name: "refresh",
			class: `size-5 `
		});
		$$renderer.push(`<!----></button> `);
		NotificationBell($$renderer, {});
		$$renderer.push(`<!----></div></div></header> <main id="contenido" class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8"><!---->`);
		$$renderer.push(`<div class="animate-rise">`);
		children($$renderer);
		$$renderer.push(`<!----></div>`);
		$$renderer.push(`<!----></main> <footer class="border-t border-border"><div class="mx-auto max-w-7xl px-4 py-4 text-xs text-muted sm:px-6"${attr_style("", { "padding-bottom": "max(1rem, env(safe-area-inset-bottom))" })}>SIGCON · Alcaldía Municipal de Jamundí — Valle del Cauca, Colombia</div></footer></div></div> <dialog id="menu-principal" aria-label="Menú principal" class="drawer m-0 h-dvh max-h-dvh w-[19rem] max-w-[85vw] border-0 border-r border-border bg-surface-strong p-0 text-ink shadow-2xl shadow-black/50 backdrop:bg-backdrop backdrop:backdrop-blur-sm lg:hidden"><div class="flex h-full flex-col"><div class="flex items-center gap-3 border-b border-border px-5 py-5">`);
		BrandMark($$renderer, { size: "md" });
		$$renderer.push(`<!----> <div class="min-w-0 flex-1"><p class="font-semibold text-ink">Alcaldía Jamundí</p> <p class="text-xs text-muted" translate="no">SIGCON</p></div> <button type="button" class="inline-flex size-9 items-center justify-center rounded-lg text-muted transition-colors hover:bg-white/5 hover:text-ink" aria-label="Cerrar menú">`);
		Icon($$renderer, { name: "close" });
		$$renderer.push(`<!----></button></div> `);
		navList($$renderer, false);
		$$renderer.push(`<!----> `);
		profileBlock($$renderer, false);
		$$renderer.push(`<!----></div></dialog>`);
	});
}
//#endregion
//#region src/routes/(app)/+layout.svelte
function _layout($$renderer, $$props) {
	let { children } = $$props;
	AppShell($$renderer, {
		children: ($$renderer) => {
			children($$renderer);
			$$renderer.push(`<!---->`);
		},
		$$slots: { default: true }
	});
}
//#endregion
export { _layout as default };
