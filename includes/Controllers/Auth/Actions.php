<?php

namespace NitroSpark\Controllers\Auth;

/**
 * Handles the NitroSpark authentication callback.
 *
 * NitroSpark's server POSTs here after a site administrator connects the
 * site from the WordPress admin. The route is public (no WP user auth)
 * because it is called server-to-server, not by the browser; it is
 * protected by a single-use, short-lived state token that only an
 * administrator of this site could have generated.
 */
class Actions {

	/**
	 * Logs a message when WP_DEBUG is enabled. No-op otherwise.
	 *
	 * @param string $message Message to log.
	 */
	private function log( string $message ): void {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[NitroSpark] ' . $message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

	/**
	 * Receives the auth_key from NitroSpark and saves it to WP options.
	 *
	 * Expected POST body (JSON):
	 * {
	 *   "auth_key": "...",   — the key NitroSpark issued for this site
	 *   "state":    "..."    — the token originally sent in the connect URL
	 * }
	 *
	 * @param \WP_REST_Request $request Current request.
	 * @return \WP_REST_Response
	 */
	public function callback( \WP_REST_Request $request ) {
		$state    = sanitize_text_field( (string) $request->get_param( 'state' ) );
		$auth_key = sanitize_text_field( (string) $request->get_param( 'auth_key' ) );

		// Verify the state token exists and has not expired.
		$transient_key = 'nitrospark_connect_state_' . $state;
		$user_id       = '' !== $state ? get_transient( $transient_key ) : false;

		if ( false === $user_id ) {
			$this->log( 'Auth callback rejected: invalid or expired state token.' );
			return new \WP_REST_Response(
				array( 'error' => 'Invalid or expired state token.' ),
				401
			);
		}

		// Token is valid — delete it immediately so it can't be reused.
		delete_transient( $transient_key );

		if ( empty( $auth_key ) ) {
			$this->log( 'Auth callback rejected: missing auth_key.' );
			return new \WP_REST_Response(
				array( 'error' => 'Missing auth_key.' ),
				400
			);
		}

		// Persist the auth key and the connected user site-wide.
		update_option( 'nitrospark_auth_key', $auth_key );
		update_option( 'nitrospark_auth_user_id', (int) $user_id );

		$this->log( 'Auth key saved for user ' . (int) $user_id . '.' );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}
}
