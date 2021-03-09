<?php
declare(strict_types=1);

namespace papi\Controller;

use papi\Documentation\RouteParametersDocGenerator;
use papi\Relation\ManyToMany;
use papi\Resource\Field\Id;
use papi\Worker\App;

abstract class ManyToManyController extends RESTController
{
    public ManyToMany $relation;

    public function __construct(App $api)
    {
        parent::__construct($api);
        $this->relation = $this->getRelation();
        $this->resourceName = $this->relation->getTableName();
    }

    abstract protected function getRelation(): ManyToMany;

    public function getEndpoint(): string
    {
        return "/$this->resourceName";
    }

    public function getRouteParameters(): array
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

    public function getQueryFilters(): array
    {
        return RouteParametersDocGenerator::generate(
            $this->relation->getFields(),
            RouteParametersDocGenerator::QUERY
        );
    }
}