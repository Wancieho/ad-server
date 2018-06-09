<?php

require_once __DIR__ . '/vendor/jacwright/restserver/source/Jacwright/RestServer/RestServer.php';
require_once __DIR__ . '/vendor/jacwright/restserver/source/Jacwright/RestServer/RestException.php';

use Jacwright\RestServer\RestServer;

spl_autoload_register(); // don't load our classes unless we use them

$mode = 'debug'; // 'debug' or 'production'
$server = new RestServer($mode);
// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass('CampaignsController', '/campaigns');

$server->handle();
