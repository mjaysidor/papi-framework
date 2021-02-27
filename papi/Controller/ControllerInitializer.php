<?php
declare(strict_types=1);

namespace papi\Controller;

use papi\Utils\ClassGetter;

class ControllerInitializer
{
    public function init($api): void
    {
        foreach (ClassGetter::getClasses('src/Controller') as $controller) {
            (new $controller($api))->init();
        }
    }
}