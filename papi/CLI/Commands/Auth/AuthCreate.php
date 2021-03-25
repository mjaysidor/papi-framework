<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Auth;

use Exception;
use papi\CLI\Command;
use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Generator\AuthGenerator;

/**
 * Creates authentication system (user validation, JWT mechanisms + optionally user resource)
 */
class AuthCreate implements Command
{
    public function getCommand(): string
    {
        return 'auth:create';
    }

    public function getDescription(): string
    {
        return 'Creates authentication system (user validation, JWT mechanisms + optionally user resource)';
    }

    public function execute(): void
    {
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
            AuthGenerator::makeVoterDirectory();
        } catch (Exception $exception) {
            ConsoleOutput::errorDie($exception->getMessage());
        }

        ConsoleOutput::success('Auth resources created!');
    }
}
