<?php

require 'vendor/autoload.php';

use papi\Config\ProjectStructure;
use papi\Controller\ControllerInitializer;
use papi\Documentation\DocGenerator;
use papi\DotEnv;
use papi\Worker\App;

$api = new App();
DotEnv::load('.env.local');
DotEnv::load();
(new ControllerInitializer)->init($api);
DocGenerator::generateOpenAPIDocs(ProjectStructure::getOpenApiDocPath(), $api->getRoutes());

$api->start();
