<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Database\Paginator\PaginatorFactory;
use papi\Relation\ManyToMany;
use papi\Relation\ManyToManyValidator;
use papi\Response\ErrorResponse;
use papi\Response\JsonResponse;
use papi\Response\NotFoundResponse;
use papi\Response\ValidationErrorResponse;
use Workerman\Protocols\Http\Request;

class ManyToManyHandler
{
    public static function createRelation(
        ManyToMany $relation,
        Request $request
    ): JsonResponse {
        $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);

        $validationErrors = (new ManyToManyValidator())->getValidationErrors($relation, $body);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($relation->exists(
            $body[$relation->rootResourceIdField],
            $body[$relation->relatedResourceIdField],
        )) {
            return new ErrorResponse('Relation already exists');
        }

        $relation->create(
            $body[$relation->rootResourceIdField],
            $body[$relation->relatedResourceIdField],
        );

        return new JsonResponse(
            201,
            $body
        );
    }

    public static function deleteRelation(
        ManyToMany $relation,
        string $rootResourceId,
        string $relatedResourceId
    ): JsonResponse {
        $response = $relation->delete(
            $rootResourceId,
            $relatedResourceId
        );

        if ($response) {
            return new JsonResponse(204);
        }

        return new NotFoundResponse();
    }

    public static function getRelation(
        ManyToMany $relation,
        Request $request,
        bool $pagination = true,
        int $paginationItems = 10
    ): JsonResponse {
        $filters = [];

        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }

        $validationErrors = (new ManyToManyQueryValidator())->getValidationErrors($relation, $filters);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($pagination === true) {
            $paginator = PaginatorFactory::getCursorPaginator($filters, $paginationItems);
            $result = $paginator->getPaginatedManyToManyResults($relation, $filters);
        } else {
            $result = $relation->get($filters);
        }

        return new JsonResponse(200, $result);
    }
}