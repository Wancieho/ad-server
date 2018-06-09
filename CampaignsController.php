<?php

use Jacwright\RestServer\RestException;

class CampaignsController {

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /
     */
    public function index() {
        return CampaignsModel::get();
    }

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /$id
     */
    public function read($id = null) {
        return CampaignsModel::get($id);
    }

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url POST /
     */
    public function create() {
        if (!isset($_POST['name'])) {
            throw new RestException(401, '`name` field must be specified');
        }

        return CampaignsModel::save(new Campaign([
                    'name' => $_POST['name'],
        ]));
    }

}
