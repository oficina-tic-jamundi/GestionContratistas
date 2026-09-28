
// this file is generated — do not edit it


declare module "svelte/elements" {
	export interface HTMLAttributes<T> {
		'data-sveltekit-keepfocus'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-noscroll'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-preload-code'?:
			| true
			| ''
			| 'eager'
			| 'viewport'
			| 'hover'
			| 'tap'
			| 'off'
			| undefined
			| null;
		'data-sveltekit-preload-data'?: true | '' | 'hover' | 'tap' | 'off' | undefined | null;
		'data-sveltekit-reload'?: true | '' | 'off' | undefined | null;
		'data-sveltekit-replacestate'?: true | '' | 'off' | undefined | null;
	}
}

export {};


declare module "$app/types" {
	type MatcherParam<M> = M extends (param : string) => param is (infer U extends string) ? U : string;

	export interface AppTypes {
		RouteId(): "/(app)" | "/" | "/(app)/account" | "/(app)/account/password" | "/(app)/activities" | "/(app)/activities/[uuid]" | "/(app)/audit" | "/(app)/contractors" | "/(app)/contractors/new" | "/(app)/contractors/[uuid]" | "/(app)/contracts" | "/(app)/contracts/new" | "/(app)/contracts/[uuid]" | "/(app)/contracts/[uuid]/edit" | "/(app)/departments" | "/(app)/history" | "/(app)/jobs" | "/login" | "/(app)/notifications" | "/(app)/payments" | "/(app)/payments/rules" | "/(app)/payments/[uuid]" | "/(app)/reports" | "/(app)/reports/[uuid]" | "/(app)/roles" | "/(app)/roles/new" | "/(app)/roles/[code]" | "/(app)/statistics" | "/(app)/users" | "/(app)/users/new" | "/(app)/users/[uuid]";
		RouteParams(): {
			"/(app)/activities/[uuid]": { uuid: string };
			"/(app)/contractors/[uuid]": { uuid: string };
			"/(app)/contracts/[uuid]": { uuid: string };
			"/(app)/contracts/[uuid]/edit": { uuid: string };
			"/(app)/payments/[uuid]": { uuid: string };
			"/(app)/reports/[uuid]": { uuid: string };
			"/(app)/roles/[code]": { code: string };
			"/(app)/users/[uuid]": { uuid: string }
		};
		LayoutParams(): {
			"/(app)": { uuid?: string | undefined; code?: string | undefined };
			"/": { uuid?: string | undefined; code?: string | undefined };
			"/(app)/account": Record<string, never>;
			"/(app)/account/password": Record<string, never>;
			"/(app)/activities": { uuid?: string | undefined };
			"/(app)/activities/[uuid]": { uuid: string };
			"/(app)/audit": Record<string, never>;
			"/(app)/contractors": { uuid?: string | undefined };
			"/(app)/contractors/new": Record<string, never>;
			"/(app)/contractors/[uuid]": { uuid: string };
			"/(app)/contracts": { uuid?: string | undefined };
			"/(app)/contracts/new": Record<string, never>;
			"/(app)/contracts/[uuid]": { uuid: string };
			"/(app)/contracts/[uuid]/edit": { uuid: string };
			"/(app)/departments": Record<string, never>;
			"/(app)/history": Record<string, never>;
			"/(app)/jobs": Record<string, never>;
			"/login": Record<string, never>;
			"/(app)/notifications": Record<string, never>;
			"/(app)/payments": { uuid?: string | undefined };
			"/(app)/payments/rules": Record<string, never>;
			"/(app)/payments/[uuid]": { uuid: string };
			"/(app)/reports": { uuid?: string | undefined };
			"/(app)/reports/[uuid]": { uuid: string };
			"/(app)/roles": { code?: string | undefined };
			"/(app)/roles/new": Record<string, never>;
			"/(app)/roles/[code]": { code: string };
			"/(app)/statistics": Record<string, never>;
			"/(app)/users": { uuid?: string | undefined };
			"/(app)/users/new": Record<string, never>;
			"/(app)/users/[uuid]": { uuid: string }
		};
		Pathname(): "/" | "/account/password" | "/activities" | `/activities/${string}` & {} | "/audit" | "/contractors" | "/contractors/new" | `/contractors/${string}` & {} | "/contracts" | "/contracts/new" | `/contracts/${string}` & {} | `/contracts/${string}/edit` & {} | "/departments" | "/history" | "/jobs" | "/login" | "/notifications" | "/payments" | "/payments/rules" | `/payments/${string}` & {} | "/reports" | `/reports/${string}` & {} | "/roles" | "/roles/new" | `/roles/${string}` & {} | "/statistics" | "/users" | "/users/new" | `/users/${string}` & {};
		ResolvedPathname(): `${"" | `/${string}`}${ReturnType<AppTypes['Pathname']>}`;
		Asset(): "/.htaccess" | "/favicon.svg" | "/robots.txt" | string & {};
	}
}