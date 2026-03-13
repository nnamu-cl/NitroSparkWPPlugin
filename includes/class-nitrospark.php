<?php
/**
 * Main NitroSpark class.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NitroSpark
 */
final class NitroSpark {

	private static ?NitroSpark $instance = null;

	public static function instance(): static {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	private function __construct() {
		$this->load_dependencies();
		$this->register_hooks();
	}

	private function load_dependencies(): void {
		// Composer autoload (loads vite-for-wp PHP helper).
		$autoload = NITROSPARK_PLUGIN_DIR . 'vendor/autoload.php';
		if ( file_exists( $autoload ) ) {
			require_once $autoload;
		}

		require_once NITROSPARK_PLUGIN_DIR . 'includes/Admin/Menu.php';
		require_once NITROSPARK_PLUGIN_DIR . 'includes/Admin/Assets.php';
	}

	private function register_hooks(): void {
		register_activation_hook( NITROSPARK_PLUGIN_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( NITROSPARK_PLUGIN_FILE, [ $this, 'deactivate' ] );

		add_action( 'init', [ $this, 'load_textdomain' ] );

		if ( is_admin() ) {
			$menu = new NitroSpark\Admin\Menu();
			$menu->register();

			$assets = new NitroSpark\Admin\Assets();
			$assets->register();
		}
	}

	public function activate(): void {
		flush_rewrite_rules();
	}

	public function deactivate(): void {
		flush_rewrite_rules();
	}

	public function load_textdomain(): void {
		load_plugin_textdomain(
			'nitrospark',
			false,
			dirname( plugin_basename( NITROSPARK_PLUGIN_FILE ) ) . '/languages'
		);
	}
}
