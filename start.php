<?php

use framework\Controller\ControllerInitializer;
use framework\Documentation\DocGenerator;
use framework\DotEnv;
use framework\Worker\App;

require_once 'vendor/autoload.php';

$api = new App('http://0.0.0.0:3000');

$api->count = 4; // process count

(new DotEnv(__DIR__.'/.env.local'))->load();
(new DotEnv(__DIR__.'/.env'))->load();
(new ControllerInitializer)->init($api);

DocGenerator::generateOpenAPIDocs(getcwd().'/doc/open_api_endpoints.yaml',$api->getRouteInfo());

$api->start();

