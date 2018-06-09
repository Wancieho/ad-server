<?php

/*
 * Campaign object class
 */

final class Campaign {

    public $id, $name;

    public function __construct(stdClass $data) {
        $this->id = isset($data->id) ? $data->id : null;
        $this->name = $data->name;

        return $this;
    }

}
