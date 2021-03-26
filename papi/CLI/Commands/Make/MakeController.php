<?php

declare(strict_types=1);

namespace papi\CLI\Commands\Make;

use Exception;
use papi\CLI\Command;
use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;
use papi\Generator\FileGenerator;

/**
 * Creates a plain controller
 */
class MakeController implements Command
{
    public function getCommand(): string
    {
        return 'make:controller';
    }

    public function getDescription(): string
    {
        return 'Creates a plain controller';
    }

    public function execute(): void
    {
        $directory = ConsoleInput::getInput('Directory (inside ' . ProjectStructure::getControllersPath() . '):');
        if (($name = ConsoleInput::getInput('Class name:')) === '') {
            ConsoleOutput::errorDie('Name cannot be empty');
        }
        try {
            FileGenerator::generateController($directory, $name);
        } catch (Exception $exception) {
            ConsoleOutput::errorDie($exception->getMessage());
        }

        ConsoleOutput::success('Controller created!');
    }
}
