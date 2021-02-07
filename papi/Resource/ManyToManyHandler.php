<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Database\Paginator\Paginator;
use papi\Database\Paginator\PaginatorFactory;
use papi\Database\PostgresDb;
use papi\Relation\ManyToMany;
use papi\Relation\ManyToManyValidator;
use papi\Response\ErrorResponse;
use papi\Response\JsonResponse;
use papi\Response\MethodNotAllowedResponse;
use papi\Response\NotFoundResponse;
use papi\Response\ValidationErrorResponse;
use Workerman\Protocols\Http\Request;

class ManyToManyHandler
{
    public static function createRelation(
        ManyToMany $relation,
        Request $request
    ): JsonResponse {
        if (! RequestMethodChecker::isPost($request)) {
            return new MethodNotAllowedResponse('POST');
        }

        $body = json_decode($request->rawBody(), true);

        $validationErrors = (new ManyToManyValidator())->getValidationErrors($relation, $body);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }
        $handler = new PostgresDb();

        if ($handler->exists(
            $relation->getTableName(),
            [
                $relation->rootResourceIdField    => $body[$relation->rootResourceIdField],
                $relation->relatedResourceIdField => $body[$relation->relatedResourceIdField],
            ]
        )) {
            return new ErrorResponse('Relation already exists');
        }
        $handler->clearAliases();

        $result = $handler->insert(
            $relation->getTableName(),
            [
                $relation->rootResourceIdField    => $body[$relation->rootResourceIdField],
                $relation->relatedResourceIdField => $body[$relation->relatedResourceIdField],
            ]
        );

        if (is_string($result)) {
            return new ErrorResponse($result);
        }

        return new JsonResponse(
            201,
            $body
        );
    }

    public static function deleteRelation(
        ManyToMany $relation,
        string $rootResourceId,
        string $relatedResourceId,
        Request $request
    ): JsonResponse {
        if (! RequestMethodChecker::isDelete($request)) {
            return new MethodNotAllowedResponse('DELETE');
        }

        $handler = new PostgresDb();
        $response = $handler
            ->delete(
                $relation->getTableName(),
                [
                    $relation->rootResourceIdField    => $rootResourceId,
                    $relation->relatedResourceIdField => $relatedResourceId,
                ]
            );
        if (is_string($response)) {
            return new ErrorResponse($response);
        }
        if ($response) {
            return new JsonResponse(204);
        }

        return new NotFoundResponse();
    }

    public static function getRelation(
        ManyToMany $relation,
        Request $request,
        ?int $pagination = Paginator::CURSOR_PAGINATION
    ): JsonResponse {
        if (! RequestMethodChecker::isGet($request)) {
            return new MethodNotAllowedResponse('GET');
        }

        $filters = [];

        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }

        $validationErrors = (new ManyToManyQueryValidator())->getValidationErrors($relation, $filters);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        $paginator = null;

        if ($pagination) {
            $paginator = PaginatorFactory::getPaginator($pagination, $filters);
            $filters = $paginator->addPaginationToFilters($filters);
        }

        $handler = new PostgresDb();
        $result = $handler
            ->select(
                $relation->getTableName(),
                $relation->getFields(),
                $filters
            );
        if ($pagination) {
            $result = $paginator->addPaginationLinks($result);
        }
        if (is_string($result)) {
            return new ErrorResponse($result);
        }

        return new JsonResponse(200, $result);
    }
}