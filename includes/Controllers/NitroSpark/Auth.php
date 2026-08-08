<?php

namespace NitroSpark\Controllers\NitroSpark;

/**
 * Permission check for all NitroSpark proxy routes.
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
	 * auth key, then runs the request as the WordPress user who connected
	 * the site. Requests without a valid key are rejected before any
	 * route callback runs.
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

		$user_id = (int) get_option( 'nitrospark_auth_user_id', 0 );
		$user    = $user_id ? get_user_by( 'id', $user_id ) : false;

		if ( ! $user || ! user_can( $user, 'edit_posts' ) ) {
			return new \WP_Error(
				'nitrospark_no_user',
				__( 'The connected NitroSpark user no longer exists or lacks permissions. Please reconnect the site.', 'nitrospark' ),
				array( 'status' => 403 )
			);
		}

		// Run the proxied request as the user who authorized the connection,
		// so core REST capability checks apply to everything we dispatch.
		wp_set_current_user( $user->ID );

		return true;
	}
}
