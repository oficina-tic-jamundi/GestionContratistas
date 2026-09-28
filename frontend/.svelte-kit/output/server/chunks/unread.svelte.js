import "./server.js";
import { o as unreadCount } from "./api2.js";
//#region src/lib/features/notifications/unread.svelte.ts
var UnreadNotifications = class {
	count = 0;
	#timer = null;
	async refresh() {
		try {
			this.count = await unreadCount();
		} catch {}
	}
	/** Inicia la consulta periódica; devuelve la función que la detiene. */
	start(intervalMs = 6e4) {
		const onVisibility = () => {
			if (document.visibilityState === "visible") this.refresh();
		};
		this.refresh();
		this.#timer = setInterval(() => {
			if (document.visibilityState === "visible") this.refresh();
		}, intervalMs);
		document.addEventListener("visibilitychange", onVisibility);
		return () => {
			if (this.#timer) clearInterval(this.#timer);
			this.#timer = null;
			document.removeEventListener("visibilitychange", onVisibility);
		};
	}
};
var unread = new UnreadNotifications();
//#endregion
export { unread as t };
