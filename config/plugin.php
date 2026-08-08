<?php
/**
 * Config file for the NitroSpark plugin.
 *
 * @package NitroSpark
 * @since 1.0.0
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| NitroSpark App URL
	|--------------------------------------------------------------------------
	|
	| Set 'nitrospark_debug' to true to point the plugin at a local
	| development instance of the NitroSpark platform. Keep it false
	| for releases.
	|
	*/

	'nitrospark_debug'    => false,

	'nitrospark_url_dev'  => 'http://localhost:3000',
	'nitrospark_url_prod' => 'https://app.nitrospark.ai',
);
