<?php

namespace App\Objects;

use stdClass;

/*
 * Banner object class
 */

final class Banner {

    public $id, $name, $campaign_id, $width, $height, $content;

    public function __construct(stdClass $data) {
        $this->id = isset($data->id) ? $data->id : null;
        $this->name = $data->name;
        $this->campaign_id = (integer) $data->campaign_id;
        $this->width = (integer) $data->width;
        $this->height = (integer) $data->height;
        $this->content = $data->content;

        return $this;
    }

}
