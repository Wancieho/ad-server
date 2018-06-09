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
        self::$mysqli = (object) self::$mysqli;
    }

}

new Config();
