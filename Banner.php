<?php

final class Banner {

    public $id, $name, $campaign_id;

    public function __construct(stdClass $data) {
        $this->id = isset($data->id) ? $data->id : null;
        $this->name = $data->name;
        $this->campaign_id = (integer) $data->campaign_id;

        return $this;
    }

}
