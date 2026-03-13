<?php

declare( strict_types=1 );

namespace NitroSpark\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Menu {

	public function register(): void {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
	}

	public function add_menu_page(): void {
		add_menu_page(
			__( 'NitroSpark', 'nitrospark' ),
			__( 'NitroSpark', 'nitrospark' ),
			'manage_options',
			'nitrospark',
			[ $this, 'render_page' ],
			'dashicons-superhero-alt',
			30
		);
	}

	public function render_page(): void {
		echo '<div id="nitrospark-admin" class="nitrospark-app"></div>';
	}
}
