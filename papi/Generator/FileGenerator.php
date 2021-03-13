<?php
declare(strict_types=1);

namespace papi\Generator;

use papi\Config\ProjectStructure;
use papi\Controller\Controller;
use papi\Controller\ResourceController;
use papi\Resource\Field\Id;
use papi\Resource\Resource;
use papi\Resource\ResourceCRUDHandler;
use papi\Response\JsonResponse;
use papi\Response\OKResponse;
use papi\Utils\CaseConverter;
use papi\Utils\PHPClassFileWriter;
use Workerman\Protocols\Http\Request;

class FileGenerator
{
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
            "return ['id' => new Id()];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getDefaultSELECTFields',
            "return ['id'];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getEditableFields',
            "return [];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getFieldValidators',
            "return [];"
        );

        $writer->write();

        self::generateResourceController($dir, $name, $customEndpoints);
    }

    private static function generateResourceController(
        string $dir,
        string $name,
        bool $customEndpoints = true
    ): void {
        $writer = new PHPClassFileWriter(
            $name.'Controller',
            $dir ? ProjectStructure::getControllersNamespace().'\\'.$dir : ProjectStructure::getControllersNamespace(),
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

    public static function generateController(
        string $dir,
        string $name
    ): void {
        $writer = new PHPClassFileWriter(
            $name,
            $dir ? ProjectStructure::getControllersNamespace().'\\'.$dir : ProjectStructure::getControllersNamespace(),
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
                return ResourceCRUDHandler::delete($this->resource, $id, $request);
            }
        );

        $this->getById(
            function (Request $request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id, $request);
            }
        );

        $this->get(
            function (Request $request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );
        ';
    }
}