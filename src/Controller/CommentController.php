<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resources\Comment;
use papi\Callbacks\AddCurrentDate;
use papi\Controller\ResourceController;
use papi\Database\PostgresDb;
use papi\Resource\ResourceCRUDHandler;
use papi\Response\JsonResponse;

class CommentController extends ResourceController
{
    protected function getResource()
    {
        return new Comment();
    }

    public function init(): void
    {
        $this->post(
            function ($request) {
                return ResourceCRUDHandler::create($this->resource, $request, new AddCurrentDate());
            }
        );

        $this->put(
            function ($request, $id) {
                return ResourceCRUDHandler::updateById($this->resource, $id, $request);
            }
        );

        $this->delete(
            function ($request, $id) {
                return ResourceCRUDHandler::deleteById($this->resource, $id, $request);
            }
        );

        $this->getById(
            function ($request, $id) {
                return ResourceCRUDHandler::getById($this->resource, $id, $request);
            }
        );

        $this->get(
            function ($request) {
//                $id = random_int(1, 1000);

                /**
                 * TODO GET BY ID
                 */
                return new JsonResponse(200, $this->resource->getById(152));
                return new JsonResponse(200, (new PostgresDb())->select('comment'), ['id=' => 152]);

                /**
                 * TODO GET COLLECTION
                 */
                return new JsonResponse(200, $this->resource->get());
                return new JsonResponse(200, (new PostgresDb())->select('comment'));

                /**
                 * TODO DELETE BY ID
                 */
                return new JsonResponse(200, [$this->resource->delete($id)]);
                return new JsonResponse(204, [(new PostgresDb())->delete('comment', ['true=' => true])]);

                /**
                 * TODO UPDATE BY ID
                 */
                return new JsonResponse(200, [$this->resource->update(5, ['content' => 'asd', 'up_votes' => 2])]);
                return new JsonResponse(
                    204,
                    [(new PostgresDb())->update('comment', ['content=' => 'asd', 'up_votes=' => 4], ['id=' => 4500])]
                );

                /**
                 * TODO CREATE NEW
                 */
                return new JsonResponse(200, [$this->resource->create(['content' => 'qweqwe', 'up_votes' => 5])]);
                return new JsonResponse(
                    204,
                    [(new PostgresDb())->insert('comment', ['content' => 'qweqwe', 'up_votes' => 5])]
                );


//                return ResourceCRUDHandler::getCollection($this->resource, $request);
            }
        );
    }
}