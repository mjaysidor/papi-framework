<?php
declare(strict_types=1);

namespace framework\Controller;

use framework\Relation\ManyToMany;
use framework\Resource\Field\Id;
use framework\Worker\App;

abstract class ManyToManyController extends RESTController
{
    public ManyToMany $relation;

    public function __construct(App $api)
    {
        parent::__construct($api);
        $this->relation = $this->getRelation();
        $this->resourceName = $this->relation->getTableNameWithoutDatabase();
    }

    abstract protected function getRelation(): ManyToMany;

    public function getEndpoint(): string
    {
        return "/$this->resourceName";
    }

    public function getEndpointIds(): array
    {
        return [
            $this->getRelation()->rootResourceIdField,
            $this->getRelation()->relatedResourceIdField,
        ];
    }

    public function getPOSTPUTBody(): array
    {
        return [
            $this->relation->rootResourceIdField    => [
                'type' => (new Id())->getPHPTypeName(),
            ],
            $this->relation->relatedResourceIdField => [
                'type' => (new Id())->getPHPTypeName(),
            ],
        ];
    }

    public function getGETResponseBody(): array
    {
        return $this->getPOSTPUTBody();
    }
}