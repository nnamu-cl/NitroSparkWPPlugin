<?php

namespace NitroSpark\Controllers\Auth;

use NitroSpark\Core\ServiceUser;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the NitroSpark authentication callback.
 *
 * NitroSpark's server POSTs here after a site administrator connects the
 * site from the WordPress admin. The request is server-to-server, so cookie
 * and nonce authentication do not apply. Instead, the route's permission
 * callback requires a single-use, short-lived state token that is only
 * created when an administrator opens the NitroSpark admin page.
 */
class Actions {

	/**
	 * Maximum accepted length of the auth key issued by NitroSpark.
	 */
	private const MAX_KEY_LENGTH = 512;

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
	 * Returns the transient key for a state token.
	 *
	 * @param \WP_REST_Request $request Current request.
	 * @return string Empty string when the request carries no usable state token.
	 */
	private function state_transient_key( \WP_REST_Request $request ): string {
		$state = (string) $request->get_param( 'state' );

		// Tokens are 32 lowercase hex characters (see Admin\Assets::get_connect_url()).
		if ( 1 !== preg_match( '/^[a-f0-9]{32}$/', $state ) ) {
			return '';
		}

		return 'nitrospark_connect_state_' . $state;
	}

	/**
	 * Permission callback for the auth callback route.
	 *
	 * Allows the request only when it carries a valid, unexpired state token
	 * that was issued to a user who can still manage the site.
	 *
	 * @param \WP_REST_Request $request Current request.
	 * @return true|\WP_Error
	 */
	public function permission( \WP_REST_Request $request ) {
		$transient_key = $this->state_transient_key( $request );
		$user_id       = '' !== $transient_key ? (int) get_transient( $transient_key ) : 0;

		if ( ! $user_id || ! user_can( $user_id, 'manage_options' ) ) {
			$this->log( 'Auth callback rejected: invalid or expired state token.' );
			return new \WP_Error(
				'nitrospark_invalid_state',
				__( 'Invalid or expired state token.', 'nitrospark' ),
				array( 'status' => 401 )
			);
		}

		return true;
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
	 * The state token has already been validated by permission().
	 *
	 * @param \WP_REST_Request $request Current request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function callback( \WP_REST_Request $request ) {
		$transient_key = $this->state_transient_key( $request );
		$user_id       = (int) get_transient( $transient_key );

		// Single use: delete the token before doing anything else.
		delete_transient( $transient_key );

		$auth_key = sanitize_text_field( (string) $request->get_param( 'auth_key' ) );

		if ( '' === $auth_key || strlen( $auth_key ) > self::MAX_KEY_LENGTH ) {
			$this->log( 'Auth callback rejected: missing or malformed auth_key.' );
			return new \WP_Error(
				'nitrospark_invalid_key',
				__( 'Missing or malformed auth_key.', 'nitrospark' ),
				array( 'status' => 400 )
			);
		}

		// NitroSpark requests run as a dedicated, least-privilege publisher
		// account, never as the administrator who approved the connection.
		$service_user = ServiceUser::ensure();
		if ( is_wp_error( $service_user ) ) {
			$this->log( 'Could not create the NitroSpark publisher account: ' . $service_user->get_error_message() );
			return new \WP_Error(
				'nitrospark_service_user_failed',
				__( 'Could not create the NitroSpark publisher account.', 'nitrospark' ),
				array( 'status' => 500 )
			);
		}

		// Persist the auth key site-wide. Not autoloaded: the key is only
		// needed on NitroSpark REST requests and the admin page.
		update_option( 'nitrospark_auth_key', $auth_key, false );
		update_option( 'nitrospark_connected_by', $user_id, false );

		$this->log( 'Auth key saved; connection approved by user ' . $user_id . '.' );

		return new \WP_REST_Response( array( 'success' => true ), 200 );
	}
}
