<?php
/**
 * Plugin Name:       NitroSpark
 * Plugin URI:        https://github.com/namusanga/NitroSparkWPPlugin
 * Description:       NitroSpark WordPress Plugin.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            namusanga
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nitrospark
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NITROSPARK_VERSION', '0.1.0' );
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
