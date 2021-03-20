<?php

declare(strict_types=1);

namespace papi\Controller;

use papi\Documentation\RouteParametersDocGenerator;
use papi\Relation\ManyToMany;
use papi\Relation\Relation;
use papi\Resource\Resource;
use papi\Resource\ResourceCRUDHandler;
use papi\Worker\App;

abstract class ResourceController extends RESTController
{
    public Resource $resource;

    public function __construct(App $api)
    {
        $this->resource = $this->getResource();
        $this->resourceName = $this->resource->getTableName();
        parent::__construct($api);
    }

    public function getUrlIdParams(): array
    {
        return ['id'];
    }

    public function getPOSTPUTBody(): array
    {
        $body = [];
        foreach ($this->resource->getEditableFields() as $fieldName) {
            $field = $this->resource->getFields()[$fieldName];
            $body[$fieldName] = [
                'type' => $field->getPHPTypeName(),
            ];
        }

        return $body;
    }

    public function getGETResponseBody(): array
    {
        $body = [];
        foreach ($this->resource->getDefaultSELECTFields() as $fieldName) {
            $field = $this->resource->getFields()[$fieldName];
            $body[$fieldName] = [
                'type' => $field->getPHPTypeName(),
            ];
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

        return RouteParametersDocGenerator::generate($filters, RouteParametersDocGenerator::QUERY);
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
                return ResourceCRUDHandler::delete($this->resource, $id);
            }
        );

        $this->getById(
            function ($request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id);
            }
        );

        $this->get(
            function ($request) {
                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );
    }
}
