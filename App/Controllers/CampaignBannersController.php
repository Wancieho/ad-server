<?php

namespace App\Controllers;

use Jacwright\RestServer\RestException;

// #TODO: would like to improve this class as it's an ugly hack but that also requires re-engineering the other controllers as per readme
// #TODO: if any banners or campaign insert failed either need to rollback entire batch or return errors in response

class CampaignBannersController {

    /**
     * @url POST /
     * @return object
     */
    public function create() {
        if (!isset($_POST['banners']) || empty($_POST['banners'])) {
            throw new RestException(401, 'Field `banners` must be specified to create a campaign');
        }

        $campaignsController = new CampaignsController();

        $campaign = $campaignsController->create();

        $bannersController = new BannersController();

        $_POST['campaign_id'] = $campaign->id;

        // start building response
        $batch = (object) [];
        $batch->id = $campaign->id;
        $batch->banners = [];

        // create banners from banners POST array
        foreach (json_decode($_POST['banners']) as $val) {
            $_POST['name'] = $val->name;
            $_POST['width'] = $val->width;
            $_POST['height'] = $val->height;
            $_POST['content'] = $val->content;

            $batch->banners[] = $bannersController->create();
        }

        return $batch;
    }

}
