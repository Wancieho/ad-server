<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;
use Config;

/*
 * handles flat file contents as JSON objects
 */

class FileHandler {

	static public $file = '';

	/*
	 * saves data into specified file
	 * 
	 * @return object
	 */

	static public function save($data = null) {
		// storage directory doesn't exist then attempt to create it
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

		// default entry ID
		$insertId = 1;

		// retrieve file contents
		$fileContents = self::get();

		// search for the highest ID and make sure new entry is sequentially increased
		foreach ($fileContents as $val) {
			if ((integer) $val->id >= $insertId) {
				$insertId = $val->id + 1;
			}
		}

		// update model object ID to match the next incremental ID
		$data->id = $insertId;

		// add new object onto original file contents JSON object array
		$fileContents[] = $data;

		// save complete JSON object back into file
		File::create(self::fileName(), json_encode($fileContents));

		return (object) ['id' => $insertId];
	}

	/*
	 * retrieves files contents, converts to JSON and returns object
	 * 
	 * @return json
	 */

	static public function get($params = null) {
		$data = [];
		$id = null;

		if (is_string($params) || is_integer($params)) {
			$id = (integer) $params;
		} elseif (isset($params->id)) {
			$id = (integer) $params->id;
		}

		// retrieve file contents and convert to JSON object
		$fileContents = json_decode(File::read(self::fileName()));

		// file has contents
		if (!is_null($fileContents)) {
			// requested parameters are related just to ID
			if (is_string($params) || is_integer($params) || isset($params->id)) {
				foreach ($fileContents as $val) {
					// file entry ID matches requested ID return single entry as object
					if ((integer) $val->id === $id) {
						$data = $val;

						break;
					}
				}
			} else { // return all file contents as array
				$data = $fileContents;
			}
		} else {
			// file is empty and specific entry requested through ID but wasn't found then return empty object
			if (is_string($params) || is_integer($params) || isset($params->id)) {
				$data = (object) $data;
			}
		}

		return $data;
	}

	/*
	 * generate file name based on this classes child class + Config's
	 */

	static private function fileName() {
		return Config::$file->folder . '/' . static::$file . Config::$file->extension;
	}

}
