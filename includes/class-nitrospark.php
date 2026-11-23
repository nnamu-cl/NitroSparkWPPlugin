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
		// Composer autoload (loads vite-for-wp PHP helper + PSR-4 classes).
		$autoload = NITROSPARK_PLUGIN_DIR . 'vendor/autoload.php';
		if ( file_exists( $autoload ) ) {
			require_once $autoload;
		}

		// NitroSpark app URL: config default, overridable in wp-config.php.
		$config = require NITROSPARK_PLUGIN_DIR . 'config/plugin.php';
		define( 'NITROSPARK_ROUTE_PREFIX', 'nitrospark/v1' );
		if ( ! defined( 'NITROSPARK_APP_URL' ) ) {
			define( 'NITROSPARK_APP_URL', untrailingslashit( $config['nitrospark_url'] ) );
		}
	}

	private function register_hooks(): void {
		register_activation_hook( NITROSPARK_PLUGIN_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( NITROSPARK_PLUGIN_FILE, [ $this, 'deactivate' ] );

		// Guards for the restricted NitroSpark publisher account.
		NitroSpark\Core\ServiceUser::register_hooks();

		// Boot REST API.
		$api = new NitroSpark\Core\Api();
		$api->init();

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

}
