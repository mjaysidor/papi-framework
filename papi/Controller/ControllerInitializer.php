<?php

declare(strict_types=1);

namespace papi\Controller;

use papi\Config\ProjectStructure;
use papi\Utils\ClassGetter;
use papi\Worker\App;

/**
 * Initializes routes defined in controllers
 */
class ControllerInitializer
{
    /**
     * Initializes routes defined in controllers
     *
     * @param App $api
     */
    public function init(App $api): void
    {
        foreach (ClassGetter::getClasses(ProjectStructure::getControllersPath()) as $controller) {
            (new $controller($api))->init();
        }
    }
}
