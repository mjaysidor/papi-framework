<?php
declare(strict_types=1);

namespace framework\Controller;

use config\Controllers;

class ControllerInitializer
{
    public function init($api): void
    {
        foreach (Controllers::getItems() as $controller) {
            (new $controller($api))->init();
        }
    }
}