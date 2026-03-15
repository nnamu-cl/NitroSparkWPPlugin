<?php

declare( strict_types=1 );

namespace NitroSpark\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Assets {

	public function register(): void {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	public function enqueue( string $hook ): void {
		// Only load on our plugin page.
		if ( 'toplevel_page_nitrospark' !== $hook ) {
			return;
		}

		$this->enqueue_vite_asset();
		$this->localize_script();
	}

	private function enqueue_vite_asset(): void {
		if ( ! function_exists( 'Kucrut\\Vite\\enqueue_asset' ) ) {
			return;
		}

		\Kucrut\Vite\enqueue_asset(
			NITROSPARK_PLUGIN_DIR . 'assets/admin/dist',
			'src/admin/main.jsx',
			[
				'handle'    => 'nitrospark-admin',
				'in-footer' => true,
			]
		);
	}

	private function localize_script(): void {
		$current_user = wp_get_current_user();

		wp_localize_script( 'nitrospark-admin', 'nitrospark', [
			'apiUrl'      => rest_url( 'nitrospark/v1/' ),
			'appUrl'      => NITROSPARK_APP_URL,
			'authKey'     => get_option( 'nitrospark_auth_key', '' ),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'userInfo'    => [
				'username' => $current_user->display_name,
				'avatar'   => get_avatar_url( $current_user->ID ),
			],
			'isConnected' => ! empty( get_option( 'nitrospark_auth_key' ) ),
			'connectUrl'  => $this->get_connect_url(),
		] );
	}

	private function get_connect_url(): string {
		$state = wp_generate_password( 32, false );
		set_transient( 'nitrospark_connect_state_' . $state, get_current_user_id(), 10 * MINUTE_IN_SECONDS );

		return add_query_arg(
			[
				'callback_url' => rawurlencode( rest_url( NITROSPARK_ROUTE_PREFIX . '/callback' ) ),
				'site_url'     => rawurlencode( get_site_url() ),
				'site_name'    => rawurlencode( get_bloginfo( 'name' ) ),
				'state'        => $state,
			],
			NITROSPARK_APP_URL . '/wordpress-connect'
		);
	}
}
