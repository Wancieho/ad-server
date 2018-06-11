<?php

namespace App\Objects;

use stdClass;

/*
 * Campaign object class
 */

final class Campaign {

    public $id, $name;

    public function __construct(stdClass $data) {
        $this->id = isset($data->id) ? (integer) $data->id : null;
        $this->name = isset($data->name) ? (string) $data->name : null;

        return $this;
    }

}
