<?php

require_once __DIR__ . '/vendor/jacwright/restserver/source/Jacwright/RestServer/RestServer.php';

use Jacwright\RestServer\RestServer;

spl_autoload_register();

$mode = 'debug';
$server = new RestServer($mode);

$server->addClass('CampaignsController', '/campaigns');
$server->addClass('BannersController', '/banners');
$server->addClass('CampaignBannersController', '/campaignAndBanners');
$server->addClass('RecommendController', '/recommend');

$server->handle();
