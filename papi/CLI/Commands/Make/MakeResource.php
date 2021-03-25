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
 * Creates a REST resource with a CRUD controller
 */
class MakeResource implements Command
{
    public function getCommand(): string
    {
        return 'make:resource';
    }

    public function getDescription(): string
    {
        return 'Creates a REST resource with a CRUD controller';
    }

    public function execute(): void
    {
        $directory = ConsoleInput::getInput('Directory (inside '.ProjectStructure::getResourcesPath().'):');
        if (($name = ConsoleInput::getInput('Class name:')) === '') {
            ConsoleOutput::errorDie('Name cannot be empty');
        }
        $customCrud = ConsoleInput::getInput(
            'Do you want a full, predefined CRUD or customizable'
            .' endpoints? (type "crud" or hit ENTER for customizable):'
        );
        $customCrud = $customCrud !== 'crud';

        try {
            FileGenerator::generateResource($directory, $name, $customCrud);
        } catch (Exception $exception) {
            ConsoleOutput::errorDie($exception->getMessage());
        }

        ConsoleOutput::success('Resource and controller created!');
    }
}
