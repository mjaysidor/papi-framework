<?php

declare(strict_types=1);

namespace papi\Controller;

use papi\Documentation\RouteParametersDocGenerator;
use papi\Relation\ManyToMany;
use papi\Resource\Field\Id;
use papi\Resource\ManyToManyHandler;
use papi\Worker\App;
use Workerman\Protocols\Http\Request;

/**
 * Controller handling many to many relation endpoints
 */
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

    public function getPOSTPUTBodyDoc(): array
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

    public function getGETResponseBodyDoc(): array
    {
        return $this->getPOSTPUTBodyDoc();
    }

    public function getQueryFiltersDoc(): array
    {
        return RouteParametersDocGenerator::generate(
            $this->relation->getFields(),
            RouteParametersDocGenerator::QUERY
        );
    }

    protected function standardCRUD(): void
    {
        $this->post(
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
        );
    }
}
