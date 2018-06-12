<?php

namespace App\Utils;

use Jacwright\RestServer\RestException;

/*
 * File system management
 */

class File {
	/*
	 * Attempt to open a specified file
	 * 
	 * @return string
	 */

	static public function readContents($file = '', $mode = 'r') {
		if ($handler = @fopen($file, $mode)) {
			if (filesize($file) > 0) {
				if ($content = @fread($handler, filesize($file))) {
					return $content;
				}
			}
		} else {
			throw new RestException(401, 'Unable to open file `' . $file . '`');
		}

		return '';
	}

	/*
	 * Check if the specified file exists
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
	 * Attempt to create the specified file
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
