<?php

namespace App\Objects;

use stdClass;

/*
 * banner object class
 */

final class Banner {

    public $id, $name, $campaign_id, $width, $height, $content;

    public function __construct(stdClass $data) {
        $this->id = isset($data->id) ? $data->id : null;
        $this->name = isset($data->name) ? (string) $data->name : null;
        $this->campaign_id = isset($data->campaign_id) ? (integer) $data->campaign_id : null;
        $this->width = isset($data->width) ? (integer) $data->width : null;
        $this->height = isset($data->height) ? (integer) $data->height : null;
        $this->content = isset($data->content) ? (string) $data->content : null;

        return $this;
    }

}
