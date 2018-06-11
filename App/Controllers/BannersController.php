<?php

namespace App\Controllers;

use Jacwright\RestServer\RestException;
use App\Models\BannersModel;
use App\Models\CampaignsModel;
use App\Objects\Banner;

class BannersController {

    /**
     * @url POST /
     * @return object
     */
    public function create() {
        if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['campaign_id']) || !isset($_POST['width']) || !isset($_POST['height']) || !isset($_POST['content'])) {
            throw new RestException(401, 'Required fields must be defined to create a banner');
        }

        // make sure campaign_id that banner is trying to link to exists
        if (is_null(CampaignsModel::get($_POST['campaign_id']))) {
            throw new RestException(401, 'Specified campaign does not exist');
        }

        return BannersModel::save(new Banner((object) [
                            'name' => $_POST['name'],
                            'campaign_id' => $_POST['campaign_id'],
                            'width' => $_POST['width'],
                            'height' => $_POST['height'],
                            'content' => $_POST['content'],
        ]));
    }

    /**
     * @url GET /
     * @return array
     */
    public function readAll() {
        $banners = BannersModel::get();

        foreach ($banners as &$val) {
            unset($val['id']);
        }

        return $banners;
    }

    /**
     * @url GET /$id
     * @return object
     */
    public function read($id = null) {
        $banner = BannersModel::get((object) ['id' => $id]);

        unset($banner->id);

        return $banner;
    }

    /**
     * @url DELETE /
     * @return object
     */
    public function deleteAll() {
        return BannersModel::delete();
    }

    /**
     * @url DELETE /$id
     * @return object
     */
    public function delete($id = null) {
        return BannersModel::delete((object) ['id' => $id]);
    }

}
