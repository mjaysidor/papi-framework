<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Callbacks\PostExecutionHandler;
use papi\Callbacks\PreExecutionBodyModifier;
use papi\Database\Paginator\PaginatorFactory;
use papi\Response\ErrorResponse;
use papi\Response\JsonResponse;
use papi\Response\NotFoundResponse;
use papi\Response\ValidationErrorResponse;
use Workerman\Protocols\Http\Request;

class ResourceCRUDHandler
{
    public static function update(
        Resource $resource,
        string $id,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null,
        ?PostExecutionHandler $postExecutionHandler = null
    ): JsonResponse {
        $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($preExecutionBodyModifier !== null) {
            $preExecutionBodyModifier->modify($body);
        }

        $validationErrors = (new Validator())->getValidationErrors($resource, $body);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        $response = $resource->update(
            $id,
            $body
        );

        if ($response === 0) {
            return new NotFoundResponse();
        }

        if ($postExecutionHandler !== null) {
            $handlerResponse = $postExecutionHandler->handle($body);
            if ($handlerResponse) {
                $body = array_merge($body, ['handler' => $handlerResponse]);
            }
        }

        return new JsonResponse(200, $body);
    }

    public static function create(
        Resource $resource,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null,
        ?PostExecutionHandler $postExecutionHandler = null
    ): JsonResponse {
        $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);

        $validationErrors = (new Validator())->getValidationErrors($resource, $body);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($preExecutionBodyModifier !== null) {
            $preExecutionBodyModifier->modify($body);
        }

        $response = $resource->create($body);

        if ($response === []) {
            return new ErrorResponse('Unknown database error');
        }

        if ($postExecutionHandler !== null) {
            $handlerResponse = $postExecutionHandler->handle($body);
            if ($handlerResponse) {
                $body = array_merge($body, ['handler' => $handlerResponse]);
            }
        }

        return new JsonResponse(
            201,
            $response,
            ['Location' => $request->host().$request->uri()."/".$response['id']]
        );
    }

    public static function delete(
        Resource $resource,
        string $id
    ): JsonResponse {
        $response = $resource->delete($id);

        if ($response === 0) {
            return new NotFoundResponse();
        }

        return new JsonResponse(204);
    }

    public static function getById(
        Resource $resource,
        string $id
    ): JsonResponse {
        $response = $resource->getById($id);

        if ($response === []) {
            return new NotFoundResponse();
        }

        return new JsonResponse(200, $response);
    }

    public static function getCollection(
        Resource $resource,
        Request $request,
        bool $pagination = true,
        int $paginationItems = 10
    ): JsonResponse {
        $filters = [];
        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }
        $validationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters);

        if ($validationErrors !== null) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($pagination === true) {
            $paginator = PaginatorFactory::getPaginator($filters, $paginationItems);
            $result = $paginator->getPaginatedResults($resource, $filters);
        } else {
            $result = $resource->get($filters);
        }

        return new JsonResponse(200, $result);
    }
}