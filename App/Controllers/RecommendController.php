<?php

namespace App\Controllers;

use Jacwright\RestServer\RestException;
use App\Models\BannersModel;

class RecommendController {

	/**
	 * @url GET /
	 */
	public function read() {
		if (!isset($_GET['width']) || empty($_GET['width']) || !isset($_GET['height']) || empty($_GET['height'])) {
			throw new RestException(401, 'Fields `width` and `height` must be defined to retrieve a recommended banner');
		}

		// #TODO: research more optimal way of doing this as it could be quite slow if MySQL has to process too many entries using RAND()
		$banner = BannersModel::get((object) [
							'where' => [
								'width' => $_GET['width'],
								'height' => $_GET['height'],
							],
							'order' => 'ORDER BY RAND()',
							'limit' => 'LIMIT 0,1'
		]);

		if (!isset($banner->id)) {
			throw new RestException(404, 'Not found');
		}

		unset($banner->id);

		return $banner;
	}

}
