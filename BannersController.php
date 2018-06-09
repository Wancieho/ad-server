<?php

use Jacwright\RestServer\RestException;

class BannersController {

    /**
     * @url GET /
     */
    public function index() {
        return BannersModel::get();
    }

    /**
     * @url GET /$id
     */
    public function read($id = null) {
        return BannersModel::get($id);
    }

    /**
     * @url POST /
     */
    public function create() {
        if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['campaign_id']) || !isset($_POST['width']) || !isset($_POST['height']) || !isset($_POST['content'])) {
            throw new RestException(401, 'Required fields must be specified to create a banner');
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
     * @url DELETE /$id
     */
    public function delete($id = null) {
        return BannersModel::delete($id);
    }

}
