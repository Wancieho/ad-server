<?php

namespace App\Models;

use App\Utils\Storage;

class BannersModel extends Storage {

    // override the ORM table class name
    static protected $store = 'bannerz';

}
