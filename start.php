<?php

require 'vendor/autoload.php';

use papi\Controller\ControllerInitializer;
use papi\Documentation\DocGenerator;
use papi\DotEnv;
use papi\Worker\App;

$api = new App('http://0.0.0.0:3000');

$api->count = 4; // process count

(new DotEnv(__DIR__.'/.env.local'))->load();
(new DotEnv(__DIR__.'/.env'))->load();
(new ControllerInitializer)->init($api);
$api->addRoute('GET', '/test', function () {
    return 'kk';
});
//DocGenerator::generateOpenAPIDocs(getcwd().'/doc/open_api_endpoints.yaml',$api->getRouteInfo());

$api->start();

