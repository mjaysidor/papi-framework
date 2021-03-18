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
        $this->relation = $this->getResource();
        $this->resourceName = $this->relation->getTableName();
        parent::__construct($api);
    }

    public function getUrlIdParams(): array
    {
        return [
            $this->relation->rootResourceIdField,
            $this->relation->relatedResourceIdField,
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
