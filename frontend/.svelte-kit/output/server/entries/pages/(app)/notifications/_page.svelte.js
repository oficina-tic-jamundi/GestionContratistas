import { T as escape_html, s as ensure_array_like, t as attr_class } from "../../../../chunks/server.js";
import { t as toasts } from "../../../../chunks/toasts.svelte.js";
import { t as resolve } from "../../../../chunks/paths.js";
import { r as invalidate } from "../../../../chunks/client.js";
import "../../../../chunks/navigation.js";
import { a as markAllRead } from "../../../../chunks/api2.js";
import { t as unread } from "../../../../chunks/unread.svelte.js";
import { i as formatDateTime, t as buildQuery } from "../../../../chunks/format.js";
import { t as Button } from "../../../../chunks/Button.js";
import { t as PageHeader } from "../../../../chunks/PageHeader.js";
import { t as Pagination } from "../../../../chunks/Pagination2.js";
//#region src/routes/(app)/notifications/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		function hrefFor(page) {
			return resolve(`/notifications?${buildQuery({
				page: page === 1 ? null : page,
				unread: data.unreadOnly ? 1 : null
			})}`);
		}
		async function readAll() {
			await markAllRead();
			await Promise.all([invalidate("app:notifications"), unread.refresh()]);
			toasts.show("Notificaciones marcadas como leídas.");
		}
		{
			function actions($$renderer) {
				Button($$renderer, {
					variant: "secondary",
					href: resolve(data.unreadOnly ? "/notifications" : "/notifications?unread=1"),
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(data.unreadOnly ? "Ver todas" : "Solo sin leer")}`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!----> `);
				Button($$renderer, {
					variant: "secondary",
					onclick: readAll,
					children: ($$renderer) => {
						$$renderer.push(`<!---->Marcar todas como leídas`);
					},
					$$slots: { default: true }
				});
				$$renderer.push(`<!---->`);
			}
			PageHeader($$renderer, {
				title: "Notificaciones",
				description: "Avisos sobre informes, pagos y tareas que le conciernen.",
				actions,
				$$slots: { actions: true }
			});
		}
		$$renderer.push(`<!----> <ul class="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-surface shadow-card">`);
		const each_array = ensure_array_like(data.result.items);
		if (each_array.length !== 0) {
			$$renderer.push("<!--[-->");
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let notification = each_array[$$index];
				$$renderer.push(`<li><button type="button" class="flex w-full gap-3 px-4 py-3 text-left text-sm hover:bg-canvas"><span${attr_class(`mt-1.5 size-2 shrink-0 rounded-full ${notification.read ? "bg-transparent" : "bg-primary"}`)} aria-hidden="true"></span> <span class="min-w-0 flex-1"><span${attr_class(`block ${notification.read ? "text-ink" : "font-semibold text-ink"}`)}>${escape_html(notification.title)} `);
				if (!notification.read) $$renderer.push(`<!--[0--><span class="sr-only">(sin leer)</span>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--></span> `);
				if (notification.body) $$renderer.push(`<!--[0--><span class="block text-muted">${escape_html(notification.body)}</span>`);
				else $$renderer.push("<!--[-1-->");
				$$renderer.push(`<!--]--> <span class="mt-0.5 block text-xs text-muted">${escape_html(formatDateTime(notification.created_at))}</span></span></button></li>`);
			}
		} else $$renderer.push(`<!--[!--><li class="px-4 py-8 text-center text-sm text-muted">${escape_html(data.unreadOnly ? "No tiene notificaciones sin leer." : "No tiene notificaciones.")}</li>`);
		$$renderer.push(`<!--]--></ul> `);
		Pagination($$renderer, {
			pagination: data.result.pagination,
			hrefFor
		});
		$$renderer.push(`<!---->`);
	});
}
//#endregion
export { _page as default };
