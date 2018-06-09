<?php

class Config {

    static public $driver = 'mysqli';
    static public $mysql = [
        'server' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'ad_server',
    ];

    public function __construct() {
        self::$mysql = (object) self::$mysql;
    }

}

new Config();
