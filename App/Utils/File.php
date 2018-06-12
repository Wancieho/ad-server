<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;

/*
 * file system management
 */

class File {
	/*
	 * open a specified file and insert data
	 * 
	 * @return void
	 */

	static public function create($file = '', $string = '') {
		if (!$handle = @fopen($file, 'w')) {
			throw new RestException(401, 'Unable to open file `' . $file . '`');
		}

		if (!fwrite($handle, $string)) {
			throw new RestException(401, 'Unable to write to file `' . $file . '`');
		}

		fclose($handle);
	}

	/*
	 * open a specified file
	 * 
	 * @return string
	 */

	static public function read($file = '', $mode = 'r') {
		if ($handle = @fopen($file, $mode)) {
			if (filesize($file) > 0) {
				if ($content = @fread($handle, filesize($file))) {
					return $content;
				}
			}
		} else {
			throw new RestException(401, 'Unable to open file `' . $file . '`');
		}

		return '';
	}

	/*
	 * check specified file exists
	 * 
	 * @return boolean
	 */

	static public function exists($file = '') {
		if (file_exists($file)) {
			return true;
		}

		return false;
	}

	/*
	 * create specified file
	 * 
	 * @return boolean
	 */

	static public function make($file = '') {
		if (@fopen($file, 'w')) {
			return true;
		}

		return false;
	}

}
