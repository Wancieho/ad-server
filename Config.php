<?php

class Config {

	static public $driver = 'file';
	static public $file = [
		'folder' => 'storage',
		'extension' => '.txt',
	];
	static public $mysqli = [
		'server' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'vw_ad_server',
	];

	public function __construct() {
		// convert array properties to objects for easier use
		self::$file = (object) self::$file;
		self::$mysqli = (object) self::$mysqli;
	}

}

new Config();
