<?php

require_once __DIR__ . '/vendor/jacwright/restserver/source/Jacwright/RestServer/RestServer.php';

use Jacwright\RestServer\RestServer;

spl_autoload_register();

$mode = 'debug';
$server = new RestServer($mode);

$server->addClass('App\Controllers\CampaignsController', '/campaigns');
$server->addClass('App\Controllers\BannersController', '/banners');
$server->addClass('App\Controllers\CampaignBannersController', '/campaignAndBanners');
$server->addClass('App\Controllers\RecommendController', '/recommend');

$server->handle();
