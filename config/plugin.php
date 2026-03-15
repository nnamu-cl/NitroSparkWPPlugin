<?php
/**
 * Config file for the plugin.
 *
 * @package WordPress Plugin Boilerplate
 * @since 1.0.0
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| Plugin Name
	|--------------------------------------------------------------------------
	|
	| The name of your plugin. This name will be used throughout the plugin.
	| It should be unique to your plugin.
	|
	*/

	'plugin_name' => 'WordPress Plugin Boilerplate',

	/*
	|--------------------------------------------------------------------------
	| NitroSpark App URL
	|--------------------------------------------------------------------------
	|
	| Switch between dev and production by toggling WP_DEBUG in wp-config.php.
	| WP_DEBUG = true  → dev (localhost)
	| WP_DEBUG = false → production
	|
	| TODO: Replace with a more robust env strategy (e.g. server-side proxy
	|       with signed requests) before public release.
	|
	*/

	// Set to true to use the dev URL, false for production.
	// Keep this false before committing/releasing.
	'nitrospark_debug'    => true,

	'nitrospark_url_dev'  => 'http://localhost:3000',
	'nitrospark_url_prod' => 'http://app.nitrospark.ai',
);
