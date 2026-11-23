<?php

namespace NitroSpark\Controllers\NitroSpark;

use NitroSpark\Core\ServiceUser;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Permission check for all NitroSpark content routes.
 *
 * These routes are called server-to-server by the NitroSpark platform,
 * never by a browser, so cookie/nonce authentication does not apply.
 * Instead, every request must carry the site-specific secret key that
 * was issued to this site during the admin-initiated connect flow
 * (see \NitroSpark\Controllers\Auth\Actions::callback()).
 */
class Auth {

	/**
	 * Validates the X-NitroSpark-Key request header against the stored
	 * auth key, then runs the request as the dedicated NitroSpark service
	 * user (never as an administrator). That user's role only allows
	 * writing posts and uploading media, so WordPress core capability checks
	 * limit every request to content work. Requests without a valid key are
	 * rejected before any route callback runs.
	 *
	 * @param \WP_REST_Request $request Current request.
	 * @return true|\WP_Error
	 */
	public function permission( \WP_REST_Request $request ) {
		$stored_key   = (string) get_option( 'nitrospark_auth_key', '' );
		$provided_key = (string) $request->get_header( 'X-NitroSpark-Key' );

		if ( '' === $stored_key || '' === $provided_key || ! hash_equals( $stored_key, $provided_key ) ) {
			return new \WP_Error(
				'nitrospark_unauthorized',
				__( 'Invalid or missing NitroSpark authentication key.', 'nitrospark' ),
				array( 'status' => 401 )
			);
		}

		$user = ServiceUser::get();

		if ( ! $user ) {
			return new \WP_Error(
				'nitrospark_no_user',
				__( 'The NitroSpark publisher account is missing or has been changed. Please reconnect the site.', 'nitrospark' ),
				array( 'status' => 403 )
			);
		}

		// Run as the restricted NitroSpark publisher so core REST capability
		// checks (edit_posts, upload_files, ...) apply to everything we dispatch.
		wp_set_current_user( $user->ID );

		return true;
	}
}
