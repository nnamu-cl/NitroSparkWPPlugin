/**
 * NitroSpark API Endpoints Registry
 *
 * Three distinct backends:
 *
 *   NEXTJS   – Next.js application
 *              Prod : https://app.nitrospark.ai
 *              Dev  : http://localhost:3000
 *              Base URL is injected at runtime via window.nitrospark.appUrl
 *              (set by Assets.php from NITROSPARK_APP_URL).
 *              Entries here store PATHS ONLY — prepend appUrl in api.js.
 *
 *   PYTHON   – Python backend
 *              https://backendserver.nitrosparkseo.com
 *              Full URLs stored here (base never changes).
 *
 *   WP_PLUGIN – WordPress REST API registered by this plugin (local).
 *               Full paths stored here (relative to WP root).
 */

// ---------------------------------------------------------------------------
// Base URL constants  (reference / documentation)
// ---------------------------------------------------------------------------

export const BASE_URLS = {
	/** Next.js app — production */
	NEXTJS_PROD: 'https://app.nitrospark.ai',
	/** Next.js app — local dev */
	NEXTJS_DEV:  'http://localhost:3000',
	/** Python backend — always this URL */
	PYTHON:      'https://backendserver.nitrosparkseo.com',
	/** WordPress REST API base path */
	WP:          '/wp-json/nitrospark/v1',
};

const PYTHON_BASE = BASE_URLS.PYTHON;
const WP_BASE     = BASE_URLS.WP;

// ---------------------------------------------------------------------------
// Next.js  — paths only (no base URL)
// Combine with window.nitrospark.appUrl at call-time inside api.js
// ---------------------------------------------------------------------------

const NEXTJS = {
	AUTH: {
		/** Initiate OAuth connection flow */
		CONNECT:    '/api/auth/connect',
		/** Revoke connection & clear stored key */
		DISCONNECT: '/api/auth/disconnect',
		/** Callback target after OAuth (handled server-side) */
		CALLBACK:   '/api/auth/callback',
	},

	USER: {
		/** GET / PUT – authenticated user profile */
		ME: '/api/user/me',
	},

	WPC: {
		/** GET – connected user's name & email */
		USER_DATA:       '/api/wpc/user-data',
		/** GET – paginated content library (query params: page, limit) */
		CONTENT_LIBRARY: '/api/wpc/content-library',
	},

	LIBRARY: {
		/** GET  – list all content library entries */
		LIST:    '/api/library',
		/** POST – create a new entry */
		CREATE:  '/api/library',
		/** GET/PUT/DELETE – single entry */
		GET:     (id) => `/api/library/${id}`,
		UPDATE:  (id) => `/api/library/${id}`,
		DELETE:  (id) => `/api/library/${id}`,
		/** POST – publish entry to WordPress */
		PUBLISH: (id) => `/api/library/${id}/publish`,
	},

	SITES: {
		LIST:   '/api/sites',
		CREATE: '/api/sites',
		GET:    (id) => `/api/sites/${id}`,
		DELETE: (id) => `/api/sites/${id}`,
	},

	AUTOGROWTH: {
		LIST:   '/api/autogrowth',
		CREATE: '/api/autogrowth',
		GET:    (id) => `/api/autogrowth/${id}`,
		UPDATE: (id) => `/api/autogrowth/${id}`,
	},
};

// ---------------------------------------------------------------------------
// Python backend  — full URLs
// ---------------------------------------------------------------------------

const PYTHON = {
	// TODO: add Python backend endpoints here as they are documented
};

// ---------------------------------------------------------------------------
// WordPress Plugin REST API  — full paths (relative to WP root)
// ---------------------------------------------------------------------------

const WP_PLUGIN = {
	AUTH: {
		/** POST – receives auth_key from NitroSpark after OAuth */
		CALLBACK: `${WP_BASE}/callback`,
		/** GET  – debug / health-check */
		DEBUG:    `${WP_BASE}/debug`,
	},

	POSTS: {
		LIST:   `${WP_BASE}/actions/posts`,
		CREATE: `${WP_BASE}/actions/posts`,
		UPDATE: (id) => `${WP_BASE}/actions/posts/${id}`,
		META:   (id) => `${WP_BASE}/actions/posts/${id}/meta`,
	},

	MEDIA: {
		LIST:   `${WP_BASE}/actions/media`,
		UPLOAD: `${WP_BASE}/actions/media`,
		GET:    (id) => `${WP_BASE}/actions/media/${id}`,
	},

	TAXONOMY: {
		CATEGORIES: `${WP_BASE}/actions/categories`,
		PAGES:      `${WP_BASE}/actions/pages`,
	},

	WOOCOMMERCE: {
		PRODUCTS:           `${WP_BASE}/actions/wc/products`,
		PRODUCT_CATEGORIES: `${WP_BASE}/actions/wc/products/categories`,
	},
};

// ---------------------------------------------------------------------------
// Exports
// ---------------------------------------------------------------------------

export const ENDPOINTS = {
	NEXTJS,
	PYTHON,
	WP_PLUGIN,
};
