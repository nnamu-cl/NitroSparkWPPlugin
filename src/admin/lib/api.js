/**
 * NitroSpark API service
 *
 * - nextjsFetch()  – calls the NitroSpark app (https://app.nitrospark.ai)
 *                    base URL comes from window.nitrospark.appUrl (set by Assets.php)
 *
 * Requests carry X-WPC-Api-Key. Console debug logs are only written when
 * WordPress runs with SCRIPT_DEBUG enabled (window.nitrospark.debug).
 */

import { ENDPOINTS } from '@/lib/endpoints';

// ---------------------------------------------------------------------------
// Config helpers
// ---------------------------------------------------------------------------

function getConfig() {
	const { appUrl = '', authKey = '', debug = false } = window.nitrospark || {};
	return { appUrl, authKey, debug: debug === true || debug === '1' };
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
	if (!getConfig().debug) return;
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
	if (!getConfig().debug) return;
	console.log(`Response [${status}]:`, data);
	console.groupEnd();
}

function logError(url, error) {
	if (!getConfig().debug) return;
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
// NitroSpark app fetch
// ---------------------------------------------------------------------------

export function nextjsFetch(path, options = {}) {
	return wpcFetch(nextjsUrl(path), options);
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
