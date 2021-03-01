<?php
declare(strict_types=1);

namespace papi\Controller;

use papi\Config\ProjectStructure;
use papi\Utils\ClassGetter;
use papi\Worker\App;

class ControllerInitializer
{
    public function init(App $api): void
    {
        foreach (ClassGetter::getClasses(ProjectStructure::getControllersPath()) as $controller) {
            (new $controller($api))->init();
        }
    }
}