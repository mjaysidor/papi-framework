<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resources\Post;
use framework\Callbacks\AddCurrentDate;
use framework\Controller\ResourceController;
use framework\Resource\ResourceCRUDHandler;

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
                return ResourceCRUDHandler::create($this->resource, $request, new AddCurrentDate());
            }
        );

        $this->get(
            function ($request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id, $request);
            }
        );
    }
}