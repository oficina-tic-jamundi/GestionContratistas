import { i as listNotifications } from "../../../../chunks/api2.js";
//#region src/routes/(app)/notifications/+page.ts
var load = async ({ url, parent, depends }) => {
	await parent();
	depends("app:notifications");
	const page = Number(url.searchParams.get("page")) || 1;
	const unreadOnly = url.searchParams.get("unread") === "1";
	return {
		result: await listNotifications({
			page,
			unread: unreadOnly
		}),
		unreadOnly
	};
};
//#endregion
export { load };
