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
			'apiUrl'   => rest_url( 'nitrospark/v1/' ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'userInfo' => [
				'username' => $current_user->display_name,
				'avatar'   => get_avatar_url( $current_user->ID ),
			],
		] );
	}
}
