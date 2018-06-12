<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;
use Config;

/*
 * interacts with MySQLi or file storage classes depending on config
 */

class Storage {
	/*
	 * property used for either MySQL table name or file storage file name
	 */

	static protected $store = '';

	static public function save($data = null) {
		return self::run('save', $data);
	}

	static public function get($params = null) {
		return self::run('get', $params);
	}

	static public function update($params = null) {
		return self::run('update', $params);
	}

	static public function delete($params = null) {
		return self::run('delete', $params);
	}

	/*
	 * chooses relevant driver to interact with
	 * 
	 * @return mixed
	 */

	static private function run($action = '', $params = null) {
		if (!isset(Config::$driver)) {
			throw new RestException(401, 'Specify Config data driver');
		}

		// #TODO: add file storage
		if (Config::$driver === 'file') {
			FileHandler::$file = self::storeName();

			switch ($action) {
				case 'save':
					return FileHandler::save($params);

				case 'get':
					return FileHandler::get($params);

				case 'update':
					return FileHandler::update($params);

				case 'delete':
					return FileHandler::delete($params);
			}
		} elseif (Config::$driver === 'mysqli') {
			MysqliHandler::$table = self::storeName();

			switch ($action) {
				case 'save':
					return MysqliHandler::save($params);

				case 'get':
					return MysqliHandler::get($params);

				case 'update':
					return MysqliHandler::update($params);

				case 'delete':
					return MysqliHandler::delete($params);
			}
		} else {
			throw new RestException(401, 'Specified Config data driver not supported');
		}
	}

	/*
	 * generate dynamic store name based on child class or child $store property
	 * 
	 * @return string
	 */

	static private function storeName() {
		if (empty(static::$store)) {
			static::$store = str_replace('model', '', strtolower(str_replace('App\\Models\\', '', get_called_class())));
		}

		return static::$store;
	}

}
