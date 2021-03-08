<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Callbacks\PostExecutionHandler;
use papi\Callbacks\PreExecutionBodyModifier;
use papi\Database\Paginator\Paginator;
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
        int $id,
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

        if ($response) {
            if ($postExecutionHandler !== null) {
                $handlerResponse = $postExecutionHandler->handle($body);
                if ($handlerResponse) {
                    $body = array_merge($body, ['handler' => $handlerResponse]);
                }
            }

            return new JsonResponse(200, $body);
        }

        return new NotFoundResponse();
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

        if ($response) {
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

        return new ErrorResponse('Unknown database error');
    }

    public static function delete(
        Resource $resource,
        int $id
    ): JsonResponse {
        $response = $resource->delete($id);

        if ($response) {
            return new JsonResponse(204);
        }

        return new NotFoundResponse();
    }

    public static function getById(
        Resource $resource,
        int $id
    ): JsonResponse {
        $response = $resource->getById($id);

        if ($response) {
            return new JsonResponse(200, $response);
        }

        return new NotFoundResponse();
    }

    public static function getCollection(
        Resource $resource,
        Request $request,
        ?int $pagination = Paginator::CURSOR_PAGINATION,
        int $paginationItems = 10
    ): JsonResponse {
        $filters = [];

        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }
        $validationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($pagination) {
            $paginator = PaginatorFactory::getPaginator($pagination, $filters, $paginationItems);
            $result = $paginator->getPaginatedResults($resource, $filters);
        } else {
            $result = $resource->get($filters);
        }

        return new JsonResponse(200, $result);
    }
}