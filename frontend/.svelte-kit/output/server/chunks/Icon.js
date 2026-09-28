import { t as attr_class, w as clsx } from "./server.js";
//#region src/lib/components/ui/Icon.svelte
function Icon($$renderer, $$props) {
	/**
	* Íconos de trazo (estilo Lucide, licencia ISC), dibujados en SVG local: sin {@html} ni
	* recursos externos. Siempre decorativos: el texto que los acompaña da el significado.
	*/
	let { name, class: className = "size-5" } = $$props;
	$$renderer.push(`<svg viewBox="0 0 24 24"${attr_class(clsx(className))} fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">`);
	if (name === "grid") $$renderer.push(`<!--[0--><rect width="7" height="7" x="3" y="3" rx="2"></rect><rect width="7" height="7" x="14" y="3" rx="2"></rect><rect width="7" height="7" x="14" y="14" rx="2"></rect><rect width="7" height="7" x="3" y="14" rx="2"></rect>`);
	else if (name === "file") $$renderer.push(`<!--[1--><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M16 13H8M16 17H8M10 9H8"></path>`);
	else if (name === "clipboard") $$renderer.push(`<!--[2--><rect width="8" height="4" x="8" y="2" rx="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="M12 11h4M12 16h4M8 11h.01M8 16h.01"></path>`);
	else if (name === "wallet") $$renderer.push(`<!--[3--><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>`);
	else if (name === "users") $$renderer.push(`<!--[4--><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>`);
	else if (name === "building") $$renderer.push(`<!--[5--><rect width="16" height="20" x="4" y="2" rx="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01M12 6h.01M16 6h.01M8 10h.01M12 10h.01M16 10h.01M8 14h.01M12 14h.01M16 14h.01"></path>`);
	else if (name === "user") $$renderer.push(`<!--[6--><circle cx="12" cy="8" r="5"></circle><path d="M20 21a8 8 0 0 0-16 0"></path>`);
	else if (name === "shield") $$renderer.push(`<!--[7--><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path><path d="m9 12 2 2 4-4"></path>`);
	else if (name === "history") $$renderer.push(`<!--[8--><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5M12 7v5l4 2"></path>`);
	else if (name === "clock") $$renderer.push(`<!--[9--><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path>`);
	else if (name === "bell") $$renderer.push(`<!--[10--><path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path>`);
	else if (name === "menu") $$renderer.push(`<!--[11--><path d="M4 6h16M4 12h16M4 18h16"></path>`);
	else if (name === "close") $$renderer.push(`<!--[12--><path d="M18 6 6 18M6 6l12 12"></path>`);
	else if (name === "key") $$renderer.push(`<!--[13--><circle cx="7.5" cy="15.5" r="5.5"></circle><path d="m21 2-9.6 9.6M15.5 7.5l3 3L22 7l-3-3"></path>`);
	else if (name === "camera") $$renderer.push(`<!--[14--><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle>`);
	else if (name === "chart") $$renderer.push(`<!--[15--><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path>`);
	else if (name === "check-list") $$renderer.push(`<!--[16--><rect width="8" height="4" x="8" y="2" rx="1"></rect><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><path d="m9 14 2 2 4-4"></path>`);
	else if (name === "mic") $$renderer.push(`<!--[17--><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v3"></path>`);
	else if (name === "upload") $$renderer.push(`<!--[18--><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><path d="M17 8l-5-5-5 5M12 3v12"></path>`);
	else if (name === "eye") $$renderer.push(`<!--[19--><path d="M2.06 12.35a1 1 0 0 1 0-.7 10.75 10.75 0 0 1 19.88 0 1 1 0 0 1 0 .7 10.75 10.75 0 0 1-19.88 0"></path><circle cx="12" cy="12" r="3"></circle>`);
	else if (name === "eye-off") $$renderer.push(`<!--[20--><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.5 13.5 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4M14.12 14.12a3 3 0 1 1-4.24-4.24"></path><path d="m2 2 20 20"></path>`);
	else if (name === "search") $$renderer.push(`<!--[21--><circle cx="11" cy="11" r="7"></circle><path d="m21 21-4.3-4.3"></path>`);
	else if (name === "inbox") $$renderer.push(`<!--[22--><path d="M22 12h-6l-2 3h-4l-2-3H2"></path><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>`);
	else if (name === "trend-up") $$renderer.push(`<!--[23--><path d="M16 7h6v6"></path><path d="m22 7-8.5 8.5-5-5L2 17"></path>`);
	else if (name === "trend-down") $$renderer.push(`<!--[24--><path d="M16 17h6v-6"></path><path d="m22 17-8.5-8.5-5 5L2 7"></path>`);
	else if (name === "refresh") $$renderer.push(`<!--[25--><path d="M3 12a9 9 0 0 1 15.5-6.2L21 8"></path><path d="M21 3v5h-5"></path><path d="M21 12a9 9 0 0 1-15.5 6.2L3 16"></path><path d="M3 21v-5h5"></path>`);
	else if (name === "lock") $$renderer.push(`<!--[26--><rect width="18" height="11" x="3" y="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path>`);
	else if (name === "alert") $$renderer.push(`<!--[27--><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path><path d="M12 9v4M12 17h.01"></path>`);
	else if (name === "chevron") $$renderer.push(`<!--[28--><path d="m6 9 6 6 6-6"></path>`);
	else if (name === "logout") $$renderer.push(`<!--[29--><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><path d="m16 17 5-5-5-5M21 12H9"></path>`);
	else $$renderer.push("<!--[-1-->");
	$$renderer.push(`<!--]--></svg>`);
}
//#endregion
export { Icon as t };
