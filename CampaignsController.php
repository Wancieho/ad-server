<?php

use Jacwright\RestServer\RestException;

class CampaignsController {

    /**
     * @url GET /
     */
    public function index() {
        $campaigns = CampaignsModel::get();

        foreach ($campaigns as &$val) {
            unset($val['id']);
        }

        return $campaigns;
    }

    /**
     * @url GET /$id
     */
    public function read($id = null) {
        $campaign = CampaignsModel::get($id);

        unset($campaign->id);

        return $campaign;
    }

    /**
     * @url POST /
     */
    public function create() {
        if (!isset($_POST['name']) || empty($_POST['name'])) {
            throw new RestException(401, 'Field `name` must be defined to create a campaign');
        }

        return CampaignsModel::save(new Campaign((object) [
                            'name' => $_POST['name'],
        ]));
    }

    /**
     * @url DELETE /$id
     */
    public function delete($id = null) {
        return CampaignsModel::delete($id);
    }

}
