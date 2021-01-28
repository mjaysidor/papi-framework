<?php
declare(strict_types=1);

namespace App\Controller;

use App\Resources\Comment;
use App\Resources\Post;
use framework\Controller\ManyToManyController;
use framework\Relation\ManyToMany;
use framework\Resource\ManyToManyHandler;

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