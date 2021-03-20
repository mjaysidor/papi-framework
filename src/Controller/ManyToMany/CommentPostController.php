<?php

declare(strict_types=1);

namespace App\Controller\ManyToMany;

use papi\Controller\ManyToManyController;
use papi\Resource\ManyToManyHandler;
use papi\Relation\ManyToMany;
use Workerman\Protocols\Http\Request;
use App\Resource\Comment;
use App\Resource\Post;

class CommentPostController extends ManyToManyController
{
    protected function getResource(): ManyToMany
    {
        return new ManyToMany(Comment::class, Post::class);
    }

    public function init(): void
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
