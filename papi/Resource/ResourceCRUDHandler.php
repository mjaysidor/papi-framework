<?php
declare(strict_types=1);

namespace papi\Resource;

use JsonException;
use papi\Callbacks\PreExecutionBodyModifier;
use papi\Database\Paginator\PaginatorFactory;
use papi\Response\JsonResponse;
use papi\Response\NotFoundResponse;
use papi\Response\OKResponse;
use papi\Response\ValidationErrorResponse;
use Workerman\Protocols\Http\Request;

class ResourceCRUDHandler
{
    public static function update(
        Resource $resource,
        string $id,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null
    ): JsonResponse {
        try {
            $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return new ValidationErrorResponse('Body cannot be empty');
        }

        if ($preExecutionBodyModifier !== null) {
            $preExecutionBodyModifier->modify($body);
        }

        if (($validationErrors = (new Validator())->getValidationErrors($resource, $body)) !== null) {
            return new ValidationErrorResponse($validationErrors);
        }

        $response = $resource->update(
            $id,
            $body
        );

        if ($response === 0) {
            return new NotFoundResponse();
        }

        return new OKResponse($body);
    }

    public static function create(
        Resource $resource,
        Request $request,
        ?PreExecutionBodyModifier $preExecutionBodyModifier = null
    ): JsonResponse {
        try {
            $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return new ValidationErrorResponse('Body cannot be empty');
        }

        if ($preExecutionBodyModifier !== null) {
            $preExecutionBodyModifier->modify($body);
        }

        if (($validationErrors = (new Validator())->getValidationErrors($resource, $body)) !== null) {
            return new ValidationErrorResponse($validationErrors);
        }

        $response = $resource->create($body);

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

        return new OKResponse($response);
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
            if (($queryValidationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters))
                !== null) {
                return new ValidationErrorResponse($queryValidationErrors);
            }
        }

        if ($pagination === true) {
            $paginator = PaginatorFactory::getPaginator($filters, $paginationItems);
            $result = $paginator->getPaginatedResults($resource, $filters);
        } else {
            $result = $resource->get($filters);
        }

        return new OKResponse($result);
    }
}