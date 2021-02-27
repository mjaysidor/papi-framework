<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Generator\FileGenerator;

$directory = ConsoleInput::getInput('Directory (inside src/Resource):');
$name = ConsoleInput::getInput('Class name:');
try {
    FileGenerator::generateController($directory, $name);
} catch (Exception $exception) {
    ConsoleOutput::errorDie($exception->getMessage());
}

ConsoleOutput::success('Controller created!');