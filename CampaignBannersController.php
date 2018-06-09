<?php

// #TODO: would like to improve this class as it's an ugly hack but that also requires re-engineering the other controllers as per readme

/*
 * Example banner key: [{"name":"banner1", "width": 200, "height": 300, "content": "lorem"}, {"name": "banner2", "width": 400, "height": 500, "content": "ipsum"}, {"name": "banner3", "width": 600, "height": 700, "content": "dolor"}]
 */

use Jacwright\RestServer\RestException;

class CampaignBannersController {

    /**
     * @url POST /
     */
    public function create() {
        if (!isset($_POST['banners']) || empty($_POST['banners'])) {
            throw new RestException(401, 'Field `banners` must be specified to create a campaign');
        }

        $campaignsController = new CampaignsController();

        $campaign = $campaignsController->create();

        $bannersController = new BannersController();

        $_POST['campaign_id'] = $campaign->id;

        $batch = (object) [];
        $batch->id = $campaign->id;
        $batch->banners = [];

        foreach (json_decode($_POST['banners']) as $val) {
            $_POST['name'] = $val->name;
            $_POST['width'] = $val->width;
            $_POST['height'] = $val->height;
            $_POST['content'] = $val->content;

            // #TODO: if any banners or campaign insert failed either need to rollback entire batch or return errors in response
            $batch->banners[] = $bannersController->create();
        }

        return $batch;
    }

}
