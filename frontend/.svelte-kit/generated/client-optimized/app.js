// in dev, this makes Vite inject its client as this module's first dependency,
// so that global constant replacements are installed before any other module
// (including user hooks) evaluates. In build it's inert.
import.meta.hot;




export { matchers } from './matchers.js';

export const nodes = [
	() => import('./nodes/0'),
	() => import('./nodes/1'),
	() => import('./nodes/2'),
	() => import('./nodes/3'),
	() => import('./nodes/4'),
	() => import('./nodes/5'),
	() => import('./nodes/6'),
	() => import('./nodes/7'),
	() => import('./nodes/8'),
	() => import('./nodes/9'),
	() => import('./nodes/10'),
	() => import('./nodes/11'),
	() => import('./nodes/12'),
	() => import('./nodes/13'),
	() => import('./nodes/14'),
	() => import('./nodes/15'),
	() => import('./nodes/16'),
	() => import('./nodes/17'),
	() => import('./nodes/18'),
	() => import('./nodes/19'),
	() => import('./nodes/20'),
	() => import('./nodes/21'),
	() => import('./nodes/22'),
	() => import('./nodes/23'),
	() => import('./nodes/24'),
	() => import('./nodes/25'),
	() => import('./nodes/26'),
	() => import('./nodes/27'),
	() => import('./nodes/28'),
	() => import('./nodes/29'),
	() => import('./nodes/30'),
	() => import('./nodes/31'),
	() => import('./nodes/32')
];

export const server_loads = [];

export const dictionary = {
		"/(app)": [4,[2],[3]],
		"/(app)/account/password": [5,[2],[3]],
		"/(app)/activities": [6,[2],[3]],
		"/(app)/activities/[uuid]": [7,[2],[3]],
		"/(app)/audit": [8,[2],[3]],
		"/(app)/contractors": [9,[2],[3]],
		"/(app)/contractors/new": [10,[2],[3]],
		"/(app)/contractors/[uuid]": [11,[2],[3]],
		"/(app)/contracts": [12,[2],[3]],
		"/(app)/contracts/new": [13,[2],[3]],
		"/(app)/contracts/[uuid]": [14,[2],[3]],
		"/(app)/contracts/[uuid]/edit": [15,[2],[3]],
		"/(app)/departments": [16,[2],[3]],
		"/(app)/history": [17,[2],[3]],
		"/(app)/jobs": [18,[2],[3]],
		"/login": [32],
		"/(app)/notifications": [19,[2],[3]],
		"/(app)/payments": [20,[2],[3]],
		"/(app)/payments/rules": [21,[2],[3]],
		"/(app)/payments/[uuid]": [22,[2],[3]],
		"/(app)/reports": [23,[2],[3]],
		"/(app)/reports/[uuid]": [24,[2],[3]],
		"/(app)/roles": [25,[2],[3]],
		"/(app)/roles/new": [26,[2],[3]],
		"/(app)/roles/[code]": [27,[2],[3]],
		"/(app)/statistics": [28,[2],[3]],
		"/(app)/users": [29,[2],[3]],
		"/(app)/users/new": [30,[2],[3]],
		"/(app)/users/[uuid]": [31,[2],[3]]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),
	
	reroute: (() => {}),
	transport: {}
};

export const decoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.decode]));
export const encoders = Object.fromEntries(Object.entries(hooks.transport).map(([k, v]) => [k, v.encode]));

export const hash = false;

export const decode = (type, value) => decoders[type](value);

export { default as root } from '../root.js';

export const get_error_template = () => import('../shared/error-template.js').then(m => m.default);