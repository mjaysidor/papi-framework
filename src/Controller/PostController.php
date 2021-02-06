<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resources\Post;
use papi\Callbacks\AddCurrentDate;
use papi\Controller\ResourceController;
use papi\Resource\ResourceCRUDHandler;

class PostController extends ResourceController
{
    protected function getResource()
    {
        return new Post();
    }

    public function init(): void
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