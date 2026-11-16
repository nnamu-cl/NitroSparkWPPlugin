<?php

namespace NitroSpark\Controllers\NitroSpark;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		$id       = (int) $request->get_param( 'id' );
		$internal = new \WP_REST_Request( 'GET', "/wp/v2/media/{$id}" );

		return rest_do_request( $internal );
	}

	/**
	 * POST /nitrospark-actions/media → POST /wp/v2/media
	 *
	 * NitroSpark sends multipart/form-data with the file in the 'file' field.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function upload( \WP_REST_Request $request ): \WP_REST_Response {
		$internal = new \WP_REST_Request( 'POST', '/wp/v2/media' );
		// Forward the uploaded file to core's attachments controller, which
		// validates the file type and checks the connected user's
		// upload_files capability.
		$internal->set_file_params( $request->get_file_params() );
		$internal->set_body_params( $request->get_body_params() );

		return rest_do_request( $internal );
	}
}
