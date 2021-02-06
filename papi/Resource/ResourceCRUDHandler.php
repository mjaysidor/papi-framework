<?php
declare(strict_types=1);

namespace papi\Resource;

use papi\Callbacks\PostExecutionHandler;
use papi\Callbacks\PreExecutionBodyModifier;
use papi\Database\Paginator\PaginatorFactory;
use papi\Response\ErrorResponse;
use papi\Response\JsonResponse;
use papi\Response\MethodNotAllowedResponse;
use papi\Response\ValidationErrorResponse;
use Workerman\Protocols\Http\Request;

class ResourceCRUDHandler
{
    public static function update(
        Resource $resource,
        $id,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null,
        ?PostExecutionHandler $postExecutionHandler = null
    ): JsonResponse {
        if (! RequestMethodChecker::isPut($request)) {
            return new MethodNotAllowedResponse('PUT');
        }

        $body = json_decode($request->rawBody(), true);

        if ($preExecutionBodyModifier) {
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

        if (is_string($response)) {
            return new ErrorResponse($response);
        }

        if ($response) {
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
            return new MethodNotAllowedResponse('POST');
        }

        $body = json_decode($request->rawBody(), true);

        $validationErrors = (new Validator())->getValidationErrors($resource, $body);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
        }

        if ($preExecutionBodyModifier) {
            $preExecutionBodyModifier->modify($body);
        }

        $response = $resource->create($body);

        if (is_string($response)) {
            return new ErrorResponse($response);
        }

        if ($response) {
            if ($postExecutionHandler) {
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
        $id,
        Request $request
    ): JsonResponse {
        if (! RequestMethodChecker::isDelete($request)) {
            return new MethodNotAllowedResponse('DELETE');
        }

        $response = $resource->delete($id);
        if (is_string($response)) {
            return new ErrorResponse($response);
        }

        if ($response) {
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
            return new MethodNotAllowedResponse('GET');
        }

        $response = $resource->getById($id);

        if (is_string($response)) {
            return new ErrorResponse($response);
        }

        if ($response) {
            return new JsonResponse(200, $response);
        }

        return new JsonResponse(404);
    }

    public static function getCollection(
        Resource $resource,
        Request $request,
        ?int $pagination = null
    ): JsonResponse {
        if (! RequestMethodChecker::isGet($request)) {
            return new MethodNotAllowedResponse('GET');
        }

        $filters = [];

        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
        }
        $validationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters);

        if ($validationErrors) {
            return new ValidationErrorResponse($validationErrors);
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