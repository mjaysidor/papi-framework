<?php
declare(strict_types=1);

namespace papi\Controller;

use papi\Documentation\OpenApiParamConverter;
use papi\Resource\Field\Id;
use papi\Worker\App;

abstract class ResourceController extends RESTController
{
    public $resource;

    public function __construct(App $api)
    {
        parent::__construct($api);
        $this->resource = $this->getResource();
        $this->resourceName = $this->resource->getTableName();
    }

    abstract protected function getResource();

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
        return OpenApiParamConverter::convertArrayToDoc(array_keys($this->resource->getFields()), OpenApiParamConverter::QUERY);
    }
}