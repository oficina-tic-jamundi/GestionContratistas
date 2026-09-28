import "./server.js";
//#region src/lib/stores/toasts.svelte.ts
var Toasts = class {
	items = [];
	#nextId = 1;
	show(message, kind = "success", durationMs = 5e3) {
		const id = this.#nextId++;
		this.items.push({
			id,
			kind,
			message
		});
		setTimeout(() => this.dismiss(id), durationMs);
	}
	dismiss(id) {
		this.items = this.items.filter((t) => t.id !== id);
	}
};
var toasts = new Toasts();
//#endregion
export { toasts as t };
