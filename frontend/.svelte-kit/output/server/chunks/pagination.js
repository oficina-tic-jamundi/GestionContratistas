//#region src/lib/api/pagination.ts
/** Convierte una respuesta de lista paginada de la API en { items, pagination }. */
function toPaginated(response) {
	const pagination = response.meta.pagination;
	return {
		items: response.data,
		pagination: pagination ?? {
			page: 1,
			per_page: response.data.length,
			total: response.data.length,
			total_pages: 1
		}
	};
}
//#endregion
export { toPaginated as t };
