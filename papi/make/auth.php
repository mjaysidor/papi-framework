<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Generator\AuthGenerator;

$generateUser = strcasecmp(ConsoleInput::getInput('Do you want to generate user resource?: [y]/n:'), 'n') !== 0;
$secret = ConsoleInput::getInput('Enter JWT Token secret::');
AuthGenerator::generateAuthConfig($secret);

if ($generateUser === true) {
    try {
        AuthGenerator::generateUserResource();
        AuthGenerator::generateUserController();
    } catch (Exception $exception) {
        ConsoleOutput::errorDie($exception->getMessage());
    }
}
try {
    AuthGenerator::generateAuthController($generateUser);
} catch (Exception $exception) {
    ConsoleOutput::errorDie($exception->getMessage());
}

ConsoleOutput::success('Auth resources created!');
