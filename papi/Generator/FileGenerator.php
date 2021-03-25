<?php

declare(strict_types=1);

namespace papi\Generator;

use papi\Config\ProjectStructure;
use papi\Controller\Controller;
use papi\Controller\ManyToManyController;
use papi\Controller\ResourceController;
use papi\Relation\ManyToMany;
use papi\Resource\Field\Id;
use papi\Resource\ManyToManyHandler;
use papi\Resource\Resource;
use papi\Resource\ResourceCRUDHandler;
use papi\Response\JsonResponse;
use papi\Response\OKResponse;
use papi\Utils\CaseConverter;
use papi\Utils\PHPClassFileWriter;
use Workerman\Protocols\Http\Request;

/**
 * Handles resource, controller classes generation
 */
class FileGenerator
{
    /**
     * Generates resource class
     *
     * @param string $dir
     * @param string $name
     * @param bool   $customEndpoints
     */
    public static function generateResource(
        string $dir,
        string $name,
        bool $customEndpoints = true
    ): void {
        $writer = new PHPClassFileWriter(
            $name,
            $dir ? ProjectStructure::getResourcesNamespace().'\\'.$dir : ProjectStructure::getResourcesNamespace(),
            $dir ? ProjectStructure::getResourcesPath().'/'.$dir : ProjectStructure::getResourcesPath(),
            'Resource',
            null
        );
        $writer->addImport(Resource::class);
        $writer->addImport(Id::class);
        $writer->addFunction(
            'public',
            'string',
            'getTableName',
            "return '".CaseConverter::camelToSnake($name)."';"
        );
        $writer->addFunction(
            'public',
            'array',
            'getFields',
            "return [
            'id' => new Id(),
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getDefaultSELECTFields',
            "return [
            'id',
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getEditableFields',
            "return [
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getPOSTValidators',
            "return [
        ];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getPUTValidators',
            "return [
        ];"
        );

        $writer->write();

        self::generateResourceController($dir, $name, $customEndpoints);
    }

    /**
     * Generates resource CRUD controller
     *
     * @param string $dir
     * @param string $name
     * @param bool   $customEndpoints
     */
    private static function generateResourceController(
        string $dir,
        string $name,
        bool $customEndpoints = true
    ): void {
        $writer = new PHPClassFileWriter(
            $name.'Controller',
            $dir ? ProjectStructure::getControllersNamespace().
                   '\\'.$dir : ProjectStructure::getControllersNamespace(),
            $dir ? ProjectStructure::getControllersPath().'/'.$dir : ProjectStructure::getControllersPath(),
            'ResourceController',
            null
        );
        $writer->addImport(ResourceController::class);
        $writer->addImport(ResourceCRUDHandler::class);
        $writer->addImport(Request::class);
        $writer->addImport(
            $dir ? ProjectStructure::getResourcesNamespace().'\\'.$dir.'\\'.$name
                : ProjectStructure::getResourcesNamespace().'\\'.$name
        );
        $writer->addFunction(
            'public',
            $name,
            'getResource',
            "return new $name();"
        );
        if ($customEndpoints) {
            $writer->addFunction(
                'public',
                'void',
                'init',
                self::getStandardResourceControllerInit()
            );
        } else {
            $writer->addFunction(
                'public',
                'void',
                'init',
                '$this->standardCRUD();'
            );
        }

        $writer->write();
    }

    /**
     * Generates plain controller
     *
     * @param string $dir
     * @param string $name
     */
    public static function generateController(
        string $dir,
        string $name
    ): void {
        $writer = new PHPClassFileWriter(
            $name,
            $dir ? ProjectStructure::getControllersNamespace().
                   '\\'.$dir : ProjectStructure::getControllersNamespace(),
            $dir ? ProjectStructure::getControllersPath().'/'.$dir : ProjectStructure::getControllersPath(),
            'Controller',
            null
        );
        $writer->addImport(Controller::class);
        $writer->addImport(JsonResponse::class);
        $writer->addImport(OKResponse::class);
        $writer->addImport(Request::class);
        $writer->addFunction(
            'public',
            'void',
            'init',
            self::getStandardControllerInit()
        );

        $writer->write();
    }

    /**
     * Generates Many To Many relation CRUD controller
     *
     * @param string $rootResource
     * @param string $relatedResource
     */
    public static function generateManyToManyController(
        string $rootResource,
        string $relatedResource
    ): void {
        $rootResourcePathName = explode('\\', $rootResource);
        $relatedResourcePathName = explode('\\', $relatedResource);
        $rootResourceClass = end($rootResourcePathName);
        $relatedResourceClass = end($relatedResourcePathName);
        $writer = new PHPClassFileWriter(
            $rootResourceClass.$relatedResourceClass.'Controller',
            ProjectStructure::getManyToManyControllersNamespace(),
            ProjectStructure::getManyToManyControllersPath(),
            'ManyToManyController',
            null
        );
        $writer->addImport(ManyToManyController::class);
        $writer->addImport(ManyToManyHandler::class);
        $writer->addImport(ManyToMany::class);
        $writer->addImport(Request::class);
        $writer->addImport($rootResource);
        $writer->addImport($relatedResource);
        $writer->addFunction(
            'protected',
            'ManyToMany',
            'getResource',
            "return new ManyToMany($rootResourceClass::class, $relatedResourceClass::class);"
        );
        $writer->addFunction(
            'public',
            'void',
            'init',
            self::getStandardManyToManyControllerInit()
        );

        $writer->write();
    }

    /**
     * Get default init() content of plain controller
     *
     * @return string
     */
    private static function getStandardControllerInit(): string
    {
        return '$this->post(
            "endpoint_post_url",
            function (Request $request) {
                return new JsonResponse(201, [\'body\'], [\'header\' => \'value\']);
            }
        );
            
        $this->get(
            "endpoint_get_url",
            function (Request $request) {
                return new OKResponse([\'GET RESPONSE\']);
            }
        );
        
        $this->put(
            "endpoint_put_url",
            function (Request $request, $id) {
                return new OKResponse([\'PUT RESPONSE\']);
            }
        );
        
        $this->delete(
            "endpoint_delete_url",
            function (Request $request, $id) {
                return new JsonResponse(204);
            }
        );';
    }

    /**
     * Get default resource controller init() content
     *
     * @return string
     */
    private static function getStandardResourceControllerInit(): string
    {
        return '$this->post(
            function (Request $request) {
                return ResourceCRUDHandler::create($this->resource, $request);
            }
        );

        $this->put(
            function (Request $request, $id) {
                return ResourceCRUDHandler::update($this->resource, $id, $request);
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
        );
        ';
    }

    /**
     * Get default many to many relation controller init() content
     *
     * @return string
     */
    private static function getStandardManyToManyControllerInit(): string
    {
        return '$this->post(
            function (Request $request) {
                return ManyToManyHandler::createRelation($this->relation, $request);
            }
        );
        
        $this->delete(
            function (Request $request, $rootResourceId, $relatedResourceId) {
                return ManyToManyHandler::deleteRelation($this->relation, $rootResourceId, $relatedResourceId);
            }
        );

        $this->get(
            function (Request $request) {
                return ManyToManyHandler::getRelation($this->relation, $request);
            }
        );';
    }
}
