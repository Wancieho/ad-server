<?php

use Jacwright\RestServer\RestException;

class DB {

    static private $handler;

    static private function connect() {
        self::$handler = @new mysqli(Config::$mysqli->server, Config::$mysqli->username, Config::$mysqli->password, Config::$mysqli->database);

        if (!is_null(self::$handler->connect_error)) {
            throw new RestException(401, 'DB connection failed: ' . self::$handler->connect_error);
        }
    }

    static public function handler() {
        if (is_null(self::$handler)) {
            self::connect();
        }

        return self::$handler;
    }

}
