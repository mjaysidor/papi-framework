<?php
declare(strict_types=1);

namespace papi\Controller;

use papi\Documentation\OpenApiParamConverter;
use papi\Relation\ManyToMany;
use papi\Relation\Relation;
use papi\Resource\Field\Id;
use papi\Resource\ResourceCRUDHandler;
use papi\Worker\App;

abstract class ResourceController extends RESTController
{
    public mixed $resource;

    public function __construct(App $api)
    {
        parent::__construct($api);
        $this->resource = $this->getResource();
        $this->resourceName = $this->resource->getTableName();
    }

    abstract protected function getResource(): mixed;

    public function getEndpoint(): string
    {
        return "/$this->resourceName";
    }

    public function getEndpointIds(): array
    {
        return ["id"];
    }

    public function getPOSTPUTBody(): array
    {
        $body = [];
        foreach ($this->resource->getEditableFields() as $fieldName) {
            if (isset($this->resource->getFields()[$fieldName])) {
                $field = $this->resource->getFields()[$fieldName];
                $body[$fieldName] = [
                    'type' => $field->getPHPTypeName(),
                ];
            } else {
                $body[$fieldName] = [
                    'type' => (new Id())->getPHPTypeName(),
                ];
            }
        }

        return $body;
    }

    public function getGETResponseBody(): array
    {
        $body = [];
        foreach ($this->resource->getDefaultReadFields() as $fieldName) {
            if (isset($this->resource->getFields()[$fieldName])) {
                $field = $this->resource->getFields()[$fieldName];
                $body[$fieldName] = [
                    'type' => $field->getPHPTypeName(),
                ];
            } else {
                $body[$fieldName] = [
                    'type' => (new Id())->getPHPTypeName(),
                ];
            }
        }

        return $body;
    }

    public function getQueryFilters(): array
    {
        $filters = [];
        foreach ($this->resource->getFields() as $key => $field) {
            if ($field instanceof ManyToMany) {
                continue;
            }

            if ($field instanceof Relation) {
                $filters[] = $field->getRelationFieldName();
                continue;
            }

            $filters[] = $key;
        }

        return OpenApiParamConverter::convertArrayToDoc($filters, OpenApiParamConverter::QUERY);
    }

    protected function standardCRUD(): void
    {
        $this->post(
            function ($request) {
                return ResourceCRUDHandler::create($this->resource, $request);
            }
        );

        $this->put(
            function ($request, $id) {
                return ResourceCRUDHandler::update($this->resource, $id, $request);
            }
        );

        $this->delete(
            function ($request, $id) {
                return ResourceCRUDHandler::delete($this->resource, $id, $request);
            }
        );

        $this->getById(
            function ($request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id, $request);
            }
        );

        $this->get(
            function ($request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );
    }
}