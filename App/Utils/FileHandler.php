<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;
use Config;

/*
 * Class for handling file data
 */

class FileHandler {

	static public $file = '';

	static public function save($data = null) {
		// Config storage directory doesn't exist then attempt to create it
		if (!File::exists(Config::$file->folder)) {
			if (@!mkdir(Config::$file->folder, 0777)) {
				throw new RestException(401, 'Unable to create directory `' . Config::$file->folder . '`');
			}
		}

		if (!File::exists(self::fileName())) {
			if (!File::make(self::fileName())) {
				throw new RestException(401, 'Unable to create file `' . self::fileName() . '`');
			}
		}

		return (object) ['id' => $data->id];
	}

	static public function get($params = null) {
		$data = [];
		$id = null;

		if (is_string($params) || is_integer($params)) {
			$id = (integer) $params;
		} elseif (isset($params->id)) {
			$id = (integer) $params->id;
		}

		if ($fileContents = json_decode(File::readContents(self::fileName()))) {
			if (is_string($params) || is_integer($params) || isset($params->id)) {
				foreach ($fileContents as $val) {
					if ((integer) $val->id === $id) {
						$data = $val;

						break;
					}
				}
			} else {
				$data = $fileContents;
			}

			return $data;
		}

		return null;
	}

	static private function fileName() {
		return Config::$file->folder . '/' . static::$file . Config::$file->extension;
	}

}
