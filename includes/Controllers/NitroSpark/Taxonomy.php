<?php

namespace NitroSpark\Controllers\NitroSpark;

/**
 * Proxy controller for WordPress Categories and Pages REST endpoints.
 */
class Taxonomy {

	/**
	 * GET /nitrospark-actions/categories → GET /wp/v2/categories
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_categories( \WP_REST_Request $request ): \WP_REST_Response {
		wp_set_current_user( (int) get_option( 'nitrospark_auth_user_id' ) );

		$internal = new \WP_REST_Request( 'GET', '/wp/v2/categories' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}

	/**
	 * GET /nitrospark-actions/pages → GET /wp/v2/pages
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_pages( \WP_REST_Request $request ): \WP_REST_Response {
		wp_set_current_user( (int) get_option( 'nitrospark_auth_user_id' ) );

		$internal = new \WP_REST_Request( 'GET', '/wp/v2/pages' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}
}
