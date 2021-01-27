<?php
declare(strict_types=1);

namespace framework\Controller;

use framework\Worker\App;

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
            $field = $this->resource->getFields()[$fieldName];
            $body[$fieldName] = [
                'type' => $field->getPHPTypeName(),
            ];
        }

        return $body;
    }
}