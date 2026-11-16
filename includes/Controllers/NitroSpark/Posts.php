<?php

namespace NitroSpark\Controllers\NitroSpark;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proxy controller for WordPress Posts REST endpoints.
 *
 * All methods forward requests to the real /wp/v2/posts endpoints via
 * internal REST dispatch — no HTTP round-trip, identical response format.
 */
class Posts {

	/**
	 * GET /nitrospark-actions/posts → GET /wp/v2/posts
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_all( \WP_REST_Request $request ): \WP_REST_Response {
		$internal = new \WP_REST_Request( 'GET', '/wp/v2/posts' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}

	/**
	 * POST /nitrospark-actions/posts → POST /wp/v2/posts
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function create( \WP_REST_Request $request ): \WP_REST_Response {
		$internal = new \WP_REST_Request( 'POST', '/wp/v2/posts' );
		$internal->set_body( $request->get_body() );
		$internal->set_header( 'Content-Type', 'application/json' );

		return rest_do_request( $internal );
	}

	/**
	 * POST /nitrospark-actions/posts/{id} → POST /wp/v2/posts/{id}
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function update( \WP_REST_Request $request ): \WP_REST_Response {
		$id       = (int) $request->get_param( 'id' );
		$internal = new \WP_REST_Request( 'POST', "/wp/v2/posts/{$id}" );
		$internal->set_body( $request->get_body() );
		$internal->set_header( 'Content-Type', 'application/json' );

		return rest_do_request( $internal );
	}

	/**
	 * POST /nitrospark-actions/posts/{id}/meta → POST /wp/v2/posts/{id}/meta
	 *
	 * Used by NitroSpark to update Elementor meta fields.
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function update_meta( \WP_REST_Request $request ): \WP_REST_Response {
		$id       = (int) $request->get_param( 'id' );
		$internal = new \WP_REST_Request( 'POST', "/wp/v2/posts/{$id}/meta" );
		$internal->set_body( $request->get_body() );
		$internal->set_header( 'Content-Type', 'application/json' );

		return rest_do_request( $internal );
	}
}
