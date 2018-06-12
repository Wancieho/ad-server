<?php

require_once __DIR__ . '/vendor/jacwright/restserver/source/Jacwright/RestServer/RestServer.php';

use Jacwright\RestServer\RestServer;

spl_autoload_register();

$_PUT = [];
parse_str(file_get_contents('php://input'), $_PUT);

$mode = 'debug';
$server = new RestServer($mode);

$server->addClass('App\Controllers\CampaignsController', '/campaigns');
$server->addClass('App\Controllers\BannersController', '/banners');
$server->addClass('App\Controllers\CampaignBannersController', '/campaignAndBanners');
$server->addClass('App\Controllers\RecommendController', '/recommend');

$server->handle();
