<?php

class Config {

    static public $driver = 'mysqli';
    static public $mysqli = [
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'ad_server',
    ];

    public function __construct() {
        // convert mysqli property to an object instead of array for easier use
        self::$mysqli = (object) self::$mysqli;
    }

}

new Config();
