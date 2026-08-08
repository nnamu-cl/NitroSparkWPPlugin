<?php

namespace NitroSpark\Controllers\NitroSpark;

/**
 * Proxy controller for WooCommerce REST endpoints.
 *
 * Methods return 503 when WooCommerce is not active so the plugin
 * works on non-WooCommerce sites without errors.
 */
class WooCommerce {

	/**
	 * GET /nitrospark-actions/wc/products → GET /wc/v3/products
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_products( \WP_REST_Request $request ): \WP_REST_Response {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return new \WP_REST_Response( array( 'error' => 'WooCommerce is not active.' ), 503 );
		}

		$internal = new \WP_REST_Request( 'GET', '/wc/v3/products' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}

	/**
	 * GET /nitrospark-actions/wc/products/categories → GET /wc/v3/products/categories
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function get_product_categories( \WP_REST_Request $request ): \WP_REST_Response {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return new \WP_REST_Response( array( 'error' => 'WooCommerce is not active.' ), 503 );
		}

		$internal = new \WP_REST_Request( 'GET', '/wc/v3/products/categories' );
		$internal->set_query_params( $request->get_query_params() );

		return rest_do_request( $internal );
	}
}
