<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resource\Comment;
use App\Resource\Post;
use papi\Controller\ManyToManyController;
use papi\Relation\ManyToMany;
use papi\Resource\ManyToManyHandler;

class CommentPostController extends ManyToManyController
{
    protected function getRelation(): ManyToMany
    {
        return new ManyToMany(Comment::class, Post::class);
    }

    public function init(): void
    {
        $this->post(
            function ($request) {
                return ManyToManyHandler::createRelation($this->relation, $request);
            }
        );

        $this->get(
            function ($request) {
                return ManyToManyHandler::getRelation($this->relation, $request);
            }
        );

        $this->delete(
            function ($request, $rootResourceId, $relatedResourceId) {
                return ManyToManyHandler::deleteRelation(
                    $this->relation,
                    $rootResourceId,
                    $relatedResourceId,
                    $request
                );
            }
        );
    }
}