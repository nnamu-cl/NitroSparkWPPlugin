<?php

namespace NitroSpark\Controllers\NitroSpark;

/**
 * Permission check for all NitroSpark proxy routes.
 */
class Auth {

	/**
	 * @param \WP_REST_Request $request
	 * @return bool
	 *
	 * TODO: Validate the auth_key from the X-NitroSpark-Key request header
	 *       and verify it matches the stored nitrospark_auth_key option
	 *       before releasing to production.
	 */
	public function permission( \WP_REST_Request $request ): bool {
		return true;
	}
}
