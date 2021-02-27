<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Generator\FileGenerator;

$directory = ConsoleInput::getInput('Directory (inside src/Resource):');
$name = ConsoleInput::getInput('Class name:');
$customCrud = ConsoleInput::getInput('Do you want a full, predefined CRUD or customizable endpoints? (type "crud" or hit ENTER for customizable):');
if ($customCrud === 'crud') {
    $customCrud = false;
} else {
    $customCrud = true;
}
try {
    FileGenerator::generateResource($directory, $name, $customCrud);
} catch (Exception $exception) {
    ConsoleOutput::errorDie($exception->getMessage());
}

ConsoleOutput::success('Resource and controller created!');