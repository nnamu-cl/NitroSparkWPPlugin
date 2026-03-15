<?php

namespace NitroSpark\Controllers\Auth;

/**
 * Handles the NitroSpark authentication callback.
 *
 * NitroSpark POSTs here after the user connects their account.
 * This route is public (no WP user auth needed) because it is called
 * by NitroSpark's server, not the browser.
 *
 * Security: the state token is the only protection for now.
 * TODO: Replace with signed requests before public release.
 */
class Actions {

	/**
	 * Receives the auth_key from NitroSpark and saves it to WP options.
	 *
	 * Expected POST body (JSON):
	 * {
	 *   "auth_key": "...",   — the key NitroSpark issued for this site
	 *   "state":    "..."    — the token originally sent in the connect URL
	 * }
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	private function log( string $message ): void {
		$logs   = get_option( 'nitrospark_debug_log', array() );
		$logs[] = '[' . date( 'Y-m-d H:i:s' ) . '] ' . $message;
		// Keep last 50 entries.
		update_option( 'nitrospark_debug_log', array_slice( $logs, -50 ) );
	}

	public function debug( \WP_REST_Request $request ) {
		return new \WP_REST_Response(
			array(
				'log'          => get_option( 'nitrospark_debug_log', array() ),
				'auth_key'     => get_option( 'nitrospark_auth_key' ) ? '(saved)' : '(empty)',
				'auth_user_id' => get_option( 'nitrospark_auth_user_id' ),
			),
			200
		);
	}

	public function callback( \WP_REST_Request $request ) {
		$state    = sanitize_text_field( $request->get_param( 'state' ) );
		$auth_key = sanitize_text_field( $request->get_param( 'auth_key' ) );

		$this->log( '/callback hit — state=' . $state . ' auth_key=' . ( $auth_key ? '(present)' : '(empty)' ) );
		do_action( 'qm/debug', '[NitroSpark] /callback hit', array(
			'state'    => $state,
			'auth_key' => $auth_key ? '(present)' : '(empty)',
		) );

		// Verify the state token exists and has not expired.
		$transient_key = 'nitrospark_connect_state_' . $state;
		$user_id       = get_transient( $transient_key );

		do_action( 'qm/debug', '[NitroSpark] state transient lookup', array(
			'transient_key' => $transient_key,
			'user_id'       => $user_id,
		) );
		$this->log( 'transient lookup — key=' . $transient_key . ' user_id=' . var_export( $user_id, true ) );

		if ( false === $user_id ) {
			$this->log( 'ERROR: invalid or expired state token — state=' . $state );
			do_action( 'qm/warning', '[NitroSpark] Invalid or expired state token', array( 'state' => $state ) );
			return new \WP_REST_Response(
				array( 'error' => 'Invalid or expired state token.' ),
				401
			);
		}

		// Token is valid — delete it immediately so it can't be reused.
		delete_transient( $transient_key );

		if ( empty( $auth_key ) ) {
			$this->log( 'ERROR: missing auth_key' );
			do_action( 'qm/warning', '[NitroSpark] Missing auth_key' );
			return new \WP_REST_Response(
				array( 'error' => 'Missing auth_key.' ),
				400
			);
		}

		// Persist the auth key and the connected user site-wide.
		update_option( 'nitrospark_auth_key', $auth_key );
		update_option( 'nitrospark_auth_user_id', (int) $user_id );

		$this->log( 'auth key saved — user_id=' . (int) $user_id );
		do_action( 'qm/info', '[NitroSpark] Auth key saved successfully', array( 'user_id' => (int) $user_id ) );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}
}
