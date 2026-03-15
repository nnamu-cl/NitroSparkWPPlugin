<?php

namespace NitroSpark\Controllers\NitroSpark;

/**
 * Proxy controller for WordPress Media REST endpoints.
 */
class Media {

	/**
	 * GET /nitrospark-actions/media → GET /wp/v2/media
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_all( \WP_REST_Request $request ): \WP_REST_Response {
		wp_set_current_user( (int) get_option( 'nitrospark_auth_user_id' ) );

		$internal = new \WP_REST_Request( 'GET', '/wp/v2/media' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}

	/**
	 * GET /nitrospark-actions/media/{id} → GET /wp/v2/media/{id}
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_one( \WP_REST_Request $request ): \WP_REST_Response {
		wp_set_current_user( (int) get_option( 'nitrospark_auth_user_id' ) );

		$id       = (int) $request->get_param( 'id' );
		$internal = new \WP_REST_Request( 'GET', "/wp/v2/media/{$id}" );

		return rest_do_request( $internal );
	}

	/**
	 * POST /nitrospark-actions/media → POST /wp/v2/media
	 *
	 * NitroSpark sends multipart/form-data with the file in the 'file' field.
	 * PHP populates $_FILES before our callback runs, so the internal dispatch
	 * picks it up via media_handle_upload('file', ...) as normal.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function upload( \WP_REST_Request $request ): \WP_REST_Response {
		wp_set_current_user( (int) get_option( 'nitrospark_auth_user_id' ) );

		$internal = new \WP_REST_Request( 'POST', '/wp/v2/media' );
		// set_file_params makes get_file_params() non-empty, which triggers
		// WP's file-upload path inside WP_REST_Attachments_Controller::create_item().
		$internal->set_file_params( $_FILES ); // phpcs:ignore WordPress.Security.NonceVerification
		$internal->set_body_params( $request->get_body_params() );

		return rest_do_request( $internal );
	}
}
