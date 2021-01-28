<?php
declare(strict_types=1);

namespace framework\Resource;

use framework\Callbacks\PostExecutionHandler;
use framework\Callbacks\PreExecutionBodyModifier;
use framework\Database\Paginator\Paginator;
use framework\Database\Paginator\PaginatorFactory;
use framework\Response\JsonResponse;
use Workerman\Protocols\Http\Request;

class ResourceCRUDHandler
{
    public static function updateById(
        Resource $resource,
        $id,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null,
        ?PostExecutionHandler $postExecutionHandler = null
    ): JsonResponse {
        if (! RequestMethodChecker::isPut($request)) {
            return new JsonResponse(405, ['Method not allowed'], ['Allow' => 'PUT']);
        }

        $stringBody = $request->rawBody();
        $body = json_decode($stringBody, true);

        if ($preExecutionBodyModifier) {
            $preExecutionBodyModifier->modify($body);
        }

        $validationErrors = (new Validator())->getValidationErrors($resource, $body);

        if ($validationErrors) {
            return new JsonResponse(400, [$validationErrors]);
        }

        try {
            $rowsAffected = $resource->updateById(
                $id,
                $body
            );
        } catch (\PDOException $exception) {
            return new JsonResponse(500, [$exception->getMessage()]);
        }

        if ($rowsAffected) {
            if ($postExecutionHandler) {
                $handlerResponse = $postExecutionHandler->handle($body);
                if ($handlerResponse) {
                    $body = array_merge($body, ['handler' => $handlerResponse]);
                }
            }

            return new JsonResponse(200, $body);
        }

        return new JsonResponse(404);
    }

    public static function create(
        Resource $resource,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null,
        ?PostExecutionHandler $postExecutionHandler = null
    ): JsonResponse {
        if (! RequestMethodChecker::isPost($request)) {
            return new JsonResponse(405, ['Method not allowed'], ['Allow' => 'POST']);
        }

        $stringBody = $request->rawBody();
        $body = json_decode($stringBody, true);

        $validationErrors = (new Validator())->getValidationErrors($resource, $body);

        if ($validationErrors) {
            return new JsonResponse(400, [$validationErrors]);
        }

        if ($preExecutionBodyModifier) {
            $preExecutionBodyModifier->modify($body);
        }

        try {
            $id = $resource->create($body);
        } catch (\PDOException $exception) {
            return new JsonResponse(500, [$exception->getMessage()]);
        }

        if ($id) {
            if ($postExecutionHandler) {
                $handlerResponse = $postExecutionHandler->handle($body);
                if ($handlerResponse) {
                    $body = array_merge($body, ['handler' => $handlerResponse]);
                }
            }

            return new JsonResponse(
                201,
                array_merge(
                    [
                        'id' => $id,
                    ],
                    $body
                ),
                ['Location' => $request->host().$request->uri()."/$id"]
            );
        }

        return new JsonResponse(500, ['Unknown database error']);
    }

    public static function deleteById(
        Resource $resource,
        $id,
        Request $request
    ): JsonResponse {
        if (! RequestMethodChecker::isDelete($request)) {
            return new JsonResponse(405, ['Method not allowed'], ['Allow' => 'DELETE']);
        }

        try {
            $rowsAffected = $resource->deleteById($id);
        } catch (\PDOException $exception) {
            return new JsonResponse(500, [$exception->getMessage()]);
        }

        if ($rowsAffected) {
            return new JsonResponse(204);
        }

        return new JsonResponse(404);
    }

    public static function getById(
        Resource $resource,
        $id,
        Request $request
    ): JsonResponse {
        if (! RequestMethodChecker::isGet($request)) {
            return new JsonResponse(405, ['Method not allowed'], ['Allow' => 'GET']);
        }

        try {
            $response = $resource->getById($id);
        } catch (\PDOException $exception) {
            return new JsonResponse(500, [$exception->getMessage()]);
        }

        if ($response) {
            return new JsonResponse(200, $response);
        }

        return new JsonResponse(404);
    }

    public static function getCollection(
        Resource $resource,
        Request $request,
        ?int $pagination = Paginator::CURSOR_PAGINATION
    ): JsonResponse {
        if (! RequestMethodChecker::isGet($request)) {
            return new JsonResponse(405, ['Method not allowed'], ['Allow' => 'GET']);
        }

        $filters = [];

        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }

        $validationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters);

        if ($validationErrors) {
            return new JsonResponse(400, [$validationErrors]);
        }

        if ($pagination) {
            $paginator = PaginatorFactory::getPaginator($pagination, $filters);
            $result = $paginator->getPaginatedResults($resource, $filters);
        } else {
            $result = $resource->get($filters);
        }

        return new JsonResponse(200, $result);
    }
}