/**
 * NitroSpark API service
 *
 * - nextjsFetch()  – calls the Next.js app (dev: localhost:3000, prod: app.nitrospark.ai)
 *                    base URL comes from window.nitrospark.appUrl (set by Assets.php)
 * - pythonFetch()  – calls the Python backend (backendserver.nitrosparkseo.com)
 *
 * Both attach X-WPC-Api-Key and emit structured console debug logs.
 */

import { ENDPOINTS } from '@/lib/endpoints';

// ---------------------------------------------------------------------------
// Config helpers
// ---------------------------------------------------------------------------

function getConfig() {
	const { appUrl = '', authKey = '' } = window.nitrospark || {};
	return { appUrl, authKey };
}

/** Build a full Next.js URL from a path stored in ENDPOINTS.NEXTJS */
function nextjsUrl(path) {
	const { appUrl } = getConfig();
	return `${appUrl}${path}`;
}

// ---------------------------------------------------------------------------
// Debug logger
// ---------------------------------------------------------------------------

function logRequest(method, url, headers) {
	const safeHeaders = {
		...headers,
		// Mask all but the last 4 chars of the API key
		'X-WPC-Api-Key': headers['X-WPC-Api-Key']
			? `${'*'.repeat(Math.max(0, headers['X-WPC-Api-Key'].length - 4))}${headers['X-WPC-Api-Key'].slice(-4)}`
			: '(missing)',
	};
	console.group(`[NitroSpark API] ➜ ${method} ${url}`);
	console.log('Headers:', safeHeaders);
}

function logResponse(url, status, data) {
	console.log(`Response [${status}]:`, data);
	console.groupEnd();
}

function logError(url, error) {
	console.error(`Error:`, error);
	console.groupEnd();
}

// ---------------------------------------------------------------------------
// Core fetch wrapper
// ---------------------------------------------------------------------------

/**
 * @param {string}      url
 * @param {RequestInit} [options]
 */
async function wpcFetch(url, options = {}) {
	const { authKey } = getConfig();
	const method = options.method ?? 'GET';

	const headers = {
		'Content-Type':  'application/json',
		'X-WPC-Api-Key': authKey,
		...options.headers,
	};

	logRequest(method, url, headers);

	let res;
	try {
		res = await fetch(url, { ...options, headers });
	} catch (networkError) {
		logError(url, networkError);
		throw networkError;
	}

	let data;
	try {
		data = await res.json();
	} catch {
		data = null;
	}

	logResponse(url, res.status, data);

	if (!res.ok) {
		throw new Error(
			`API error ${res.status} ${res.statusText}${data?.message ? `: ${data.message}` : ''}`
		);
	}

	return data;
}

// ---------------------------------------------------------------------------
// Next.js fetch  (dev → localhost:3000 / prod → app.nitrospark.ai)
// ---------------------------------------------------------------------------

export function nextjsFetch(path, options = {}) {
	return wpcFetch(nextjsUrl(path), options);
}

// ---------------------------------------------------------------------------
// Python backend fetch  (always backendserver.nitrosparkseo.com)
// ---------------------------------------------------------------------------

export function pythonFetch(path, options = {}) {
	const { authKey } = getConfig();
	return wpcFetch(`https://backendserver.nitrosparkseo.com${path}`, {
		...options,
		headers: {
			'X-WPC-Api-Key': authKey,
			...options.headers,
		},
	});
}

// ---------------------------------------------------------------------------
// User
// ---------------------------------------------------------------------------

/**
 * GET /api/wpc/user-data
 * @returns {Promise<import('@/types/content-library').UserProfile>}
 */
export async function fetchUserData() {
	const data = await nextjsFetch(ENDPOINTS.NEXTJS.WPC.USER_DATA);
	return {
		name:  data.wpc_user_name  ?? '',
		email: data.wpc_user_email ?? '',
	};
}

// ---------------------------------------------------------------------------
// Content Library
// ---------------------------------------------------------------------------

export const PAGE_SIZE = 10;

/**
 * GET /api/wpc/content-library?page=N&limit=10
 * @param {number} page  1-based
 * @returns {Promise<{ items: import('@/types/content-library').ContentLibraryEntry[], total: number }>}
 */
export async function fetchContentLibrary(page = 1) {
	const url = new URL(nextjsUrl(ENDPOINTS.NEXTJS.WPC.CONTENT_LIBRARY));
	url.searchParams.set('page',  String(page));
	url.searchParams.set('limit', String(PAGE_SIZE));

	const data = await wpcFetch(url.toString());

	const items = (data.wpc_content_items ?? []).sort((a, b) => {
		if (!a.created_at && !b.created_at) return 0;
		if (!a.created_at) return 1;   // a is null → goes lower
		if (!b.created_at) return -1;  // b is null → goes lower
		return new Date(b.created_at) - new Date(a.created_at);
	});

	return {
		items,
		total: data.wpc_total ?? 0,
	};
}
