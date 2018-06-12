<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;
use mysqli;
use Config;

/*
 * MySQLi DB connector which acts almost like a singleton
 */

class DB {

	static private $handler;

	/*
	 * connect to DB and set class static handler property
	 * 
	 * @return void
	 */

	static private function connect() {
		self::$handler = @new mysqli(Config::$mysqli->server, Config::$mysqli->username, Config::$mysqli->password, Config::$mysqli->database);

		if (!is_null(self::$handler->connect_error)) {
			throw new RestException(401, 'DB connection failed: ' . self::$handler->connect_error);
		}
	}

	/*
	 * check if DB connected
	 * 
	 * @return object
	 */

	static public function handler() {
		// if handler has not been created then create now
		if (is_null(self::$handler)) {
			self::connect();
		}

		return self::$handler;
	}

}
