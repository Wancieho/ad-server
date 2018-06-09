<?php

use Jacwright\RestServer\RestException;

class RecommendController {

    /**
     * @url GET /
     */
    public function read() {
        if (!isset($_GET['width']) || empty($_GET['width']) || !isset($_GET['height']) || empty($_GET['height'])) {
            throw new RestException(401, 'Fields `width` and `height` must be defined to retrieve a recommended banner');
        }

        $banner = BannersModel::get($_GET, 'ORDER BY RAND()');

        if (is_null($banner)) {
            throw new RestException(404, 'Not found');
        }

        unset($banner->id);

        return $banner;
    }

}
