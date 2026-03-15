/**
 * NitroSpark – Content Library DTOs
 *
 * JSDoc type definitions used across the admin dashboard.
 * These mirror the shapes returned by the NitroSpark Platform API.
 */

// ---------------------------------------------------------------------------
// ContentLibraryEntry  (trimmed DTO – display-only fields)
// ---------------------------------------------------------------------------

/**
 * @typedef {'draft' | 'published' | 'failed' | 'scheduled'} ContentStatus
 */

/**
 * @typedef {Object} ContentLibraryEntry
 * @property {string}        user_id        - Owner's platform user ID
 * @property {string}        title          - Article title
 * @property {string}        description    - Short description / excerpt
 * @property {string}        content        - Full HTML/Markdown body
 * @property {string[]}      key_points     - Bullet-point highlights
 * @property {string}        domain         - Target site domain (e.g. "myblog.com")
 * @property {ContentStatus} status         - Publication status
 * @property {string}        cover_image_url - URL of the hero/cover image
 * @property {any[]}         categories     - WordPress category objects
 * @property {string}        created_at     - ISO 8601 creation timestamp
 * @property {string}        updated_at     - ISO 8601 last-updated timestamp
 */

// ---------------------------------------------------------------------------
// UserProfile  (trimmed DTO from /api/user/me)
// ---------------------------------------------------------------------------

/**
 * @typedef {Object} UserProfile
 * @property {string} user_id   - Platform user ID
 * @property {string} name      - Display name
 * @property {string} email     - Email address
 * @property {string} [avatar]  - Optional avatar URL
 */

// Export empty objects so consumers can import the module
// (types live only in JSDoc annotations at runtime)
export {};
