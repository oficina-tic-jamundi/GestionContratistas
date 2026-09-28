import { T as escape_html, c as head, o as derived, s as ensure_array_like } from "../../../chunks/server.js";
import "../../../chunks/paths.js";
import "../../../chunks/navigation.js";
import { t as Icon } from "../../../chunks/Icon.js";
import { t as BrandMark } from "../../../chunks/BrandMark.js";
import { n as ApiError } from "../../../chunks/api.js";
import "../../../chunks/session.svelte.js";
import { t as Button } from "../../../chunks/Button.js";
import { t as Alert } from "../../../chunks/Alert.js";
import { t as TextField } from "../../../chunks/TextField.js";
import { t as fieldErrors } from "../../../chunks/forms.js";
//#region src/routes/login/+page.svelte
function _page($$renderer, $$props) {
	$$renderer.component(($$renderer) => {
		let { data } = $$props;
		let email = "";
		let password = "";
		let submitting = false;
		let error = null;
		const message = derived(() => error instanceof ApiError ? error.kind === "validation" ? null : error.message : null);
		/** Lo que el sistema hace, en palabras del usuario. Solo acompaña; no es publicidad. */
		const features = [
			{
				icon: "check-list",
				text: "Sus actividades y el avance de cada contrato, al día."
			},
			{
				icon: "file",
				text: "Informes con acta y registro fotográfico, sin papeles."
			},
			{
				icon: "shield",
				text: "Cada acción queda registrada, con su nombre y su fecha."
			}
		];
		let $$settled = true;
		let $$inner_renderer;
		function $$render_inner($$renderer) {
			head("1x05zx6", $$renderer, ($$renderer) => {
				$$renderer.title(($$renderer) => {
					$$renderer.push(`<title>Iniciar sesión · SIGCON</title>`);
				});
			});
			$$renderer.push(`<div class="grid min-h-dvh lg:grid-cols-[1.05fr_1fr]"><aside class="relative hidden overflow-hidden border-r border-border lg:flex lg:flex-col lg:justify-between lg:p-12"><div class="pointer-events-none absolute -top-32 -left-24 size-[32rem] rounded-full bg-primary/10 blur-3xl" aria-hidden="true"></div> <div class="pointer-events-none absolute -right-32 -bottom-40 size-[34rem] rounded-full bg-[#1d4ed8]/15 blur-3xl" aria-hidden="true"></div> <div class="relative flex items-center gap-3">`);
			BrandMark($$renderer, { size: "md" });
			$$renderer.push(`<!----> <div><p class="font-semibold text-ink">Alcaldía Municipal de Jamundí</p> <p class="text-xs text-muted">Valle del Cauca, Colombia</p></div></div> <div class="relative max-w-lg"><p class="label-eyebrow" translate="no">SIGCON</p> <h1 class="mt-3 text-4xl font-bold tracking-tight text-ink">Sistema de Gestión de Contratistas</h1> <p class="mt-4 text-base text-muted">Contratos, actividades, informes y pagos de la Alcaldía en un solo lugar.</p> <ul class="mt-8 space-y-4"><!--[-->`);
			const each_array = ensure_array_like(features);
			for (let $$index = 0, $$length = each_array.length; $$index < $$length; $$index++) {
				let feature = each_array[$$index];
				$$renderer.push(`<li class="flex items-start gap-3 text-sm text-ink"><span class="grid size-9 shrink-0 place-items-center rounded-xl bg-primary-soft text-primary" aria-hidden="true">`);
				Icon($$renderer, {
					name: feature.icon,
					class: "size-5"
				});
				$$renderer.push(`<!----></span> <span class="pt-2">${escape_html(feature.text)}</span></li>`);
			}
			$$renderer.push(`<!--]--></ul></div> <p class="relative text-xs text-muted">© ${escape_html((/* @__PURE__ */ new Date()).getFullYear())} Alcaldía Municipal de Jamundí — Valle del Cauca</p></aside> <div class="flex flex-col items-center justify-center px-4 py-10 sm:px-8"><main class="w-full max-w-md"><div class="mb-8 flex flex-col items-center text-center lg:hidden">`);
			BrandMark($$renderer, { size: "lg" });
			$$renderer.push(`<!----> <h1 class="mt-6 text-2xl font-bold tracking-tight text-ink">Alcaldía de Jamundí</h1> <p class="mt-1 text-sm font-medium text-primary">Sistema de Gestión de Contratistas</p></div> <div class="animate-rise rounded-3xl border border-border bg-surface p-6 shadow-pop sm:p-8"><h2 class="text-2xl font-bold tracking-tight text-ink">Iniciar sesión</h2> <p class="mt-1.5 text-sm text-muted">Ingrese sus credenciales institucionales.</p> <div class="mt-6 space-y-3">`);
			if (data.expired && true) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "info",
					children: ($$renderer) => {
						$$renderer.push(`<!---->Su sesión finalizó. Inicie sesión nuevamente para continuar.`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--> `);
			if (message()) {
				$$renderer.push("<!--[0-->");
				Alert($$renderer, {
					variant: "danger",
					children: ($$renderer) => {
						$$renderer.push(`<!---->${escape_html(message())}`);
					},
					$$slots: { default: true }
				});
			} else $$renderer.push("<!--[-1-->");
			$$renderer.push(`<!--]--></div> <form class="mt-6 space-y-5" novalidate="">`);
			TextField($$renderer, {
				label: "Correo institucional",
				type: "email",
				name: "email",
				autocomplete: "username",
				inputmode: "email",
				spellcheck: "false",
				placeholder: "usuario@jamundi.gov.co",
				required: true,
				errors: fieldErrors(error, "email"),
				get value() {
					return email;
				},
				set value($$value) {
					email = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			TextField($$renderer, {
				label: "Contraseña",
				type: "password",
				name: "password",
				autocomplete: "current-password",
				placeholder: "••••••••",
				required: true,
				errors: fieldErrors(error, "password"),
				get value() {
					return password;
				},
				set value($$value) {
					password = $$value;
					$$settled = false;
				}
			});
			$$renderer.push(`<!----> `);
			Button($$renderer, {
				type: "submit",
				size: "lg",
				loading: submitting,
				class: "w-full",
				children: ($$renderer) => {
					$$renderer.push(`<!---->Ingresar al sistema`);
				},
				$$slots: { default: true }
			});
			$$renderer.push(`<!----></form> <p class="mt-6 text-center text-sm text-muted">¿Olvidó su contraseña? Solicite al administrador del sistema que la restablezca.</p></div> <p class="mt-8 text-center text-xs text-muted lg:hidden">© ${escape_html((/* @__PURE__ */ new Date()).getFullYear())} Alcaldía Municipal de Jamundí — Valle del Cauca</p></main></div></div>`);
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
