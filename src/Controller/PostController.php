<?php

declare(strict_types=1);

namespace App\Controller;

use App\Resource\Post;
use papi\Controller\ResourceController;
use papi\Resource\ResourceCRUDHandler;
use Workerman\Protocols\Http\Request;

class PostController extends ResourceController
{
    public function getResource(): Post
    {
        return new Post();
    }

    public function init(): void
    {
        $this->post(
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
    }
}
