<?php

namespace NitroSpark\Core;

use NitroSpark\Libs\API\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Api {

	public function init() {
		Config::set_route_file( NITROSPARK_PLUGIN_DIR . 'includes/Routes/Api.php' )
			->set_namespace( 'NitroSpark\Api' )
			->init();
	}
}
