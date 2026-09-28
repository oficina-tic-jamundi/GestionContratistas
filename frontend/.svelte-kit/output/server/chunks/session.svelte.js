import { o as derived } from "./server.js";
import { t as resolve } from "./paths.js";
import { n as goto } from "./client.js";
import "./navigation.js";
import { n as ApiError, t as api } from "./api.js";
//#region src/lib/features/auth/api.ts
async function login(email, password) {
	return (await api.post("/auth/login", {
		email,
		password
	})).data;
}
async function fetchProfile() {
	return (await api.get("/auth/me")).data;
}
async function logout() {
	await api.post("/auth/logout");
}
//#endregion
//#region src/lib/features/auth/session.svelte.ts
var Session = class {
	profile = null;
	#user = derived(() => this.profile?.user ?? null);
	get user() {
		return this.#user();
	}
	set user($$value) {
		return this.#user($$value);
	}
	#isAuthenticated = derived(() => this.profile !== null);
	get isAuthenticated() {
		return this.#isAuthenticated();
	}
	set isAuthenticated($$value) {
		return this.#isAuthenticated($$value);
	}
	#mustChangePassword = derived(() => this.profile?.user.must_change_password ?? false);
	get mustChangePassword() {
		return this.#mustChangePassword();
	}
	set mustChangePassword($$value) {
		return this.#mustChangePassword($$value);
	}
	#permissions = derived(() => new Set(this.profile?.permissions ?? []));
	#loaded = false;
	#pending = null;
	can(...permissions) {
		return permissions.every((p) => this.#permissions().has(p));
	}
	canAny(...permissions) {
		return permissions.some((p) => this.#permissions().has(p));
	}
	/** Carga el perfil una sola vez (peticiones concurrentes comparten la misma promesa). */
	ensure() {
		if (this.#loaded) return Promise.resolve(this.profile);
		this.#pending ??= fetchProfile().then((profile) => this.set(profile)).catch((error) => {
			if (error instanceof ApiError && error.status === 401) {
				this.clear();
				return null;
			}
			throw error;
		}).finally(() => {
			this.#pending = null;
		});
		return this.#pending;
	}
	async login(email, password) {
		return this.set(await login(email, password));
	}
	async logout() {
		try {
			await logout();
		} finally {
			this.clear();
		}
	}
	set(profile) {
		this.profile = profile;
		this.#loaded = true;
		return profile;
	}
	clear() {
		this.profile = null;
		this.#loaded = true;
	}
};
var session = new Session();
api.configure({
	csrfToken: () => session.profile?.csrf_token ?? null,
	onAuthError: (error) => {
		if (!session.isAuthenticated) return;
		if (error.code === "password_change_required") {
			goto(resolve("/account/password"));
			return;
		}
		session.clear();
		const redirect = encodeURIComponent(location.pathname + location.search);
		goto(resolve(`/login?expired=1&redirect=${redirect}`));
	}
});
//#endregion
export { session as t };
