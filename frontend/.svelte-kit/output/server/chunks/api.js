//#region src/lib/api/errors.ts
var ApiError = class extends Error {
	kind;
	status;
	code;
	fieldErrors;
	requestId;
	constructor(message, kind, status, code, fieldErrors = [], requestId = null) {
		super(message);
		this.kind = kind;
		this.status = status;
		this.code = code;
		this.fieldErrors = fieldErrors;
		this.requestId = requestId;
		this.name = "ApiError";
	}
	/** Errores de un campo específico, para mostrarlos junto al input correspondiente. */
	errorsFor(field) {
		return this.fieldErrors.filter((e) => e.field === field).map((e) => e.message);
	}
};
function kindFromStatus(status) {
	if (status === 0) return "network";
	if (status === 401) return "authentication";
	if (status === 403) return "authorization";
	if (status === 404) return "not_found";
	if (status === 409) return "business";
	if (status === 422) return "validation";
	if (status === 429) return "rate_limit";
	if (status === 502 || status === 503 || status === 504) return "integration";
	return "server";
}
var NETWORK_ERROR_MESSAGE = "No fue posible comunicarse con el servidor. Verifique su conexión a internet e intente nuevamente.";
//#endregion
//#region src/lib/api/client.ts
var UNSAFE_METHODS = /* @__PURE__ */ new Set([
	"POST",
	"PUT",
	"PATCH",
	"DELETE"
]);
/**
* Cliente HTTP de la API. Único lugar del frontend que llama a fetch().
*
* - Envía cookies de sesión (mismo origen) y nunca guarda tokens en localStorage.
* - Normaliza toda respuesta al formato estándar y lanza ApiError en caso de fallo.
* - La autorización real ocurre SIEMPRE en el backend.
*/
var ApiClient = class {
	baseUrl;
	fetchFn;
	hooks = {};
	constructor(config) {
		this.baseUrl = config.baseUrl.replace(/\/+$/, "");
		this.fetchFn = config.fetch ?? ((...args) => globalThis.fetch(...args));
	}
	configure(hooks) {
		this.hooks = {
			...this.hooks,
			...hooks
		};
	}
	get(path, options = {}) {
		return this.request("GET", path, options);
	}
	post(path, body, options = {}) {
		return this.request("POST", path, {
			...options,
			body
		});
	}
	put(path, body, options = {}) {
		return this.request("PUT", path, {
			...options,
			body
		});
	}
	patch(path, body, options = {}) {
		return this.request("PATCH", path, {
			...options,
			body
		});
	}
	delete(path, options = {}) {
		return this.request("DELETE", path, options);
	}
	async request(method, path, options = {}) {
		const headers = { Accept: "application/json" };
		let body;
		const csrf = UNSAFE_METHODS.has(method) ? this.hooks.csrfToken?.() : null;
		if (csrf) headers["X-CSRF-Token"] = csrf;
		if (options.body instanceof FormData) body = options.body;
		else if (options.body !== void 0) {
			headers["Content-Type"] = "application/json";
			body = JSON.stringify(options.body);
		}
		let response;
		try {
			response = await this.fetchFn(this.buildUrl(path, options.query), {
				method,
				headers,
				body,
				credentials: "same-origin",
				signal: options.signal
			});
		} catch (cause) {
			if (cause instanceof DOMException && cause.name === "AbortError") throw cause;
			throw new ApiError(NETWORK_ERROR_MESSAGE, "network", 0, "network_error");
		}
		const envelope = await this.parse(response);
		if (envelope?.success === true) return envelope;
		const requestId = envelope?.meta.request_id ?? response.headers.get("X-Request-Id");
		const error = new ApiError(envelope?.message ?? "No fue posible procesar la solicitud.", kindFromStatus(response.status), response.status, envelope?.meta.code ?? "unexpected_response", envelope?.errors ?? [], requestId);
		if (error.status === 401 || error.code === "password_change_required") this.hooks.onAuthError?.(error);
		throw error;
	}
	buildUrl(path, query) {
		const url = `${this.baseUrl}/${path.replace(/^\/+/, "")}`;
		if (!query) return url;
		const params = new URLSearchParams();
		for (const [key, value] of Object.entries(query)) if (value !== void 0 && value !== null && value !== "") params.set(key, String(value));
		const qs = params.toString();
		return qs ? `${url}?${qs}` : url;
	}
	async parse(response) {
		if (!(response.headers.get("Content-Type") ?? "").includes("application/json")) return null;
		try {
			return await response.json();
		} catch {
			return null;
		}
	}
};
//#endregion
//#region src/lib/api/index.ts
/** Instancia compartida. La URL base es relativa: la API vive en el mismo origen. */
var api = new ApiClient({ baseUrl: "/api/v1" });
//#endregion
export { ApiError as n, api as t };
