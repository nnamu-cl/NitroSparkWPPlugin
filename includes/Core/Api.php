<?php

namespace NitroSpark\Core;

use NitroSpark\Libs\API\Config;

class Api {

	public function init() {
		Config::set_route_file( NITROSPARK_PLUGIN_DIR . 'includes/Routes/Api.php' )
			->set_namespace( 'NitroSpark\Api' )
			->init();
	}
}
