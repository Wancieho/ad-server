<?php

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
     * @url GET /test/$id
     */
    public function read($id = null) {
        return CampaignsModel::get($id);
    }

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /$id
     */
    public function create() {
        return CampaignsModel::save(new Campaign([
                    'name' => 'Megadeth',
        ]));
    }

}
