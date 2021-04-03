<?php

declare(strict_types=1);

namespace papi\Generator;

use papi\Auth\AuthController;
use papi\Callbacks\AddRole;
use papi\Callbacks\EncodePassword;
use papi\Config\AuthConfig;
use papi\Config\ProjectStructure;
use papi\Controller\ResourceController;
use papi\Database\PostgresDb;
use papi\Resource\Field\Id;
use papi\Resource\Field\Varchar;
use papi\Resource\Resource;
use papi\Resource\ResourceCRUDHandler;
use papi\Utils\PasswordEncoder;
use papi\Utils\PHPClassFileWriter;
use papi\Validator\NotBlank;
use RuntimeException;
use Workerman\Protocols\Http\Request;

/**
 * Generates files needed for authentication system
 */
class AuthGenerator
{
    public const USER_RESOURCE_NAME = 'User';

    /**
     * Generate "User" resource class
     */
    public static function generateUserResource(): void
    {
        $writer = new PHPClassFileWriter(
            self::USER_RESOURCE_NAME,
            ProjectStructure::getResourcesNamespace() . '\\Auth',
            ProjectStructure::getResourcesPath() . '/Auth',
            'Resource',
            null
        );
        $writer->addImport(Resource::class);
        $writer->addImport(NotBlank::class);
        $writer->addImport(Varchar::class);
        $writer->addImport(Id::class);
        $writer->addFunction(
            'public',
            'string',
            'getTableName',
            "return 'users';"
        );
        $writer->addFunction(
            'public',
            'array',
            'getFields',
            "return [
            'id' => new Id(),
            'username' => new Varchar(30, 'unique'),
            'roles' => new Varchar(100),
            'password' => new Varchar(110)
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getDefaultSELECTFields',
            "return [
            'id',
            'username',
            'roles'
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getEditableFields',
            "return [
            'username',
            'password',
            'roles'
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getPOSTValidators',
            "return [
            'username' => [new NotBlank()],
            'password' => [new NotBlank()],
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getPUTValidators',
            "return [
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getRoles',
            "return [
            'ROLE_ADMIN',
            'ROLE_USER',
        ];"
        );

        $writer->write();
    }

    /**
     * Create directory for JWT Voters
     */
    public static function createVoterDirectory(): void
    {
        $dir = ProjectStructure::getVoterPath();
        if (! is_dir($dir)) {
            if (! mkdir($concurrentDirectory = $dir, 0777, true) && ! is_dir($concurrentDirectory)) {
                throw new RuntimeException("Directory $concurrentDirectory was not created");
            }
        }
    }

    /**
     * Generate Authentication system config file
     *
     * @param string $secret
     */
    public static function generateAuthConfig(string $secret): void
    {
        $writer = new PHPClassFileWriter(
            'AuthConfig',
            ProjectStructure::getConfigNamespace(),
            ProjectStructure::getConfigPath(),
            implements: '\\' . AuthConfig::class
        );
        $writer->addFunction(
            'public static',
            'string',
            'getSecret',
            "return '$secret';"
        );

        $writer->write();
    }

    /**
     * Generate "User" resource CRUD controller
     */
    public static function generateUserController(): void
    {
        $resourceName = self::USER_RESOURCE_NAME;
        $writer = new PHPClassFileWriter(
            'UserController',
            ProjectStructure::getControllersNamespace() . '\\Auth',
            ProjectStructure::getControllersPath() . '/Auth',
            'ResourceController'
        );
        $writer->addImport(ProjectStructure::getResourcesNamespace() . "\\Auth\\$resourceName");
        $writer->addImport(EncodePassword::class);
        $writer->addImport(AddRole::class);
        $writer->addImport(ResourceController::class);
        $writer->addImport(ResourceCRUDHandler::class);
        $writer->addImport(Request::class);
        $writer->addFunction(
            'public',
            $resourceName,
            'getResource',
            "return new $resourceName();"
        );
        $writer->addFunction(
            'public',
            'void',
            'init',
            self::getUserControllerInit()
        );

        $writer->write();
    }

    /**
     * Get content of init() for "User" resource controller
     *
     * @return string
     */
    private static function getUserControllerInit(): string
    {
        return '$this->post(
            function (Request $request) {
                return ResourceCRUDHandler::create($this->resource, $request, [new EncodePassword(), new AddRole()]);
            }
        );

        $this->put(
            function (Request $request, $id) {
                return ResourceCRUDHandler::update($this->resource, $id, $request, [new EncodePassword()]);
            }
        );

        $this->delete(
            function (Request $request, $id) {
                return ResourceCRUDHandler::delete($this->resource, $id);
            }
        );

        $this->getById(
            function (Request $request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id);
            }
        );

        $this->get(
            function (Request $request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );';
    }

    /**
     * Generate controller handling JWT authentication
     *
     * @param bool $withUserResource
     */
    public static function generateAuthController(bool $withUserResource = false): void
    {
        $resourceName = self::USER_RESOURCE_NAME;
        $writer = new PHPClassFileWriter(
            'AuthController',
            ProjectStructure::getControllersNamespace() . '\\Auth',
            ProjectStructure::getControllersPath() . '/Auth',
            '\\' . AuthController::class,
            null
        );

        if ($withUserResource === true) {
            $writer->addImport(ProjectStructure::getResourcesNamespace() . "\\Auth\\$resourceName");
            $writer->addImport(PasswordEncoder::class);
            $writer->addImport(PostgresDb::class);
            $writer->addVariable('private', 'array', 'userData');
            $writer->addFunction(
                'protected',
                'bool',
                'credentialsValid',
                'if (isset($requestBody[\'username\'], $requestBody[\'password\']) === false) {
            return false;
        }
        $user = (new User())->get(
            new PostgresDb(),
            [
                \'username\' => $requestBody[\'username\'],
            ],
            [\'*\']
        );

        if (isset($user[0]) !== true) {
            return false;
        }

        $this->userData = $user[0];

        return password_verify($requestBody[\'password\'], $this->userData[\'password\']);',
                [
                    '?array $requestBody',
                ]
            );
            $writer->addFunction(
                'protected',
                'array',
                'getPayload',
                "return [
            'roles' => \$this->userData['roles']
        ];",
                [
                    '?array $requestBody',
                ]
            );
        } else {
            $writer->addFunction(
                'protected',
                'bool',
                'credentialsValid',
                "return true;",
                [
                    '?array $requestBody',
                ]
            );
            $writer->addFunction(
                'protected',
                'array',
                'getPayload',
                "return [];",
                [
                    '?array $requestBody',
                ]
            );
        }

        $writer->addFunction(
            'protected',
            'array',
            'getOpenApiDocRequestBody',
            "return [
            'username' => [
                'type' => 'string',
            ],
            'password' => [
                'type' => 'string',
            ],
        ];"
        );

        $writer->write();
    }
}
