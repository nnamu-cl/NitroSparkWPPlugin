<?php
/**
 * Plugin Name:       NitroSpark
 * Plugin URI:        https://nitrospark.ai/
 * Description:       Connect your WordPress site to NitroSpark AI for automated content publishing, internal linking, SEO optimization, and WooCommerce product analysis.
 * Version:           1.0.2
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            NitroSpark AI
 * Author URI:        https://nitrospark.ai/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nitrospark
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NITROSPARK_VERSION', '1.0.2' );
define( 'NITROSPARK_PLUGIN_FILE', __FILE__ );
define( 'NITROSPARK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NITROSPARK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once NITROSPARK_PLUGIN_DIR . 'includes/class-nitrospark.php';

/**
 * Returns the main plugin instance.
 */
function nitrospark(): NitroSpark {
	return NitroSpark::instance();
}

nitrospark();
