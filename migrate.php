<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use config\Migrations;

foreach (Migrations::getItems() as $migration) {
    (new $migration)->migrate();
}
