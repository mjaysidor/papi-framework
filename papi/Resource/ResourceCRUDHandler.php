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

/**
 * Handles CRUD operations on resources
 */
class ResourceCRUDHandler
{
    /**
     * Update resource
     *
     * @param string                     $id
     * @param Request                    $request
     * @param PreExecutionBodyModifier[] $preExecutionBodyModifier
     *
     * @return JsonResponse
     */
    public static function update(
        Resource $resource,
        string $id,
        Request $request,
        array $preExecutionBodyModifier = []
    ): JsonResponse {
        try {
            $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return new ValidationErrorResponse('Body cannot be empty');
        }

        foreach ($preExecutionBodyModifier as $modifier) {
            $modifier->modify($body);
        }

        if (($validationErrors = (new ResourceValidator())->getPUTValidationErrors($resource, $body)) !== null) {
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

    /**
     * Create resource
     *
     * @param Request                    $request
     * @param PreExecutionBodyModifier[] $preExecutionBodyModifier
     *
     * @return JsonResponse
     * @throws JsonException
     */
    public static function create(
        Resource $resource,
        Request $request,
        array $preExecutionBodyModifier = []
    ): JsonResponse {
        try {
            $body = json_decode($request->rawBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return new ValidationErrorResponse('Body cannot be empty');
        }

        foreach ($preExecutionBodyModifier as $modifier) {
            $modifier->modify($body);
        }

        if (($validationErrors = (new ResourceValidator())->getPOSTValidationErrors($resource, $body)) !== null) {
            return new ValidationErrorResponse($validationErrors);
        }

        $response = $resource->create($body);

        return new JsonResponse(
            201,
            $response,
            ['Location' => $request->host() . $request->uri() . "/" . $response['id']]
        );
    }

    /**
     * Delete resource
     *
     * @param string $id
     *
     * @return JsonResponse
     * @throws JsonException
     */
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

    /**
     * Get resource by id
     *
     * @param string   $id
     * @param bool     $cache
     * @param int|null $cacheTtl
     *
     * @return JsonResponse
     */
    public static function getById(
        Resource $resource,
        string $id,
        bool $cache = false,
        ?int $cacheTtl = 300
    ): JsonResponse {
        $response = $resource->getById($id, cache: $cache, cacheTtl: $cacheTtl);

        if ($response === []) {
            return new NotFoundResponse();
        }

        return new OKResponse($response);
    }

    /**
     * Get resources
     *
     * @param Request  $request
     * @param bool     $cache
     * @param bool     $pagination
     * @param int|null $cacheTtl
     * @param int      $paginationItems
     *
     * @return JsonResponse
     */
    public static function getCollection(
        Resource $resource,
        Request $request,
        bool $cache = false,
        bool $pagination = true,
        ?int $cacheTtl = 300,
        int $paginationItems = 10
    ): JsonResponse {
        $filters = [];
        if ($stringQuery = $request->queryString()) {
            parse_str($stringQuery, $filters);
            if (
                ($queryValidationErrors = (new ResourceQueryValidator())->getValidationErrors($resource, $filters))
                !== null
            ) {
                return new ValidationErrorResponse($queryValidationErrors);
            }
        }

        if ($pagination === true) {
            $paginator = PaginatorFactory::getPaginator($filters, $paginationItems);
            $result = $paginator->getPaginatedResults($resource, $filters, $cache, $cacheTtl);
        } else {
            $result = $resource->get(
                $filters,
                orderBy: $filters['orderBy'] ?? null,
                order: $filters['order'] ?? null,
                cache: $cache,
                cacheTtl: $cacheTtl
            );
        }

        return new OKResponse($result);
    }
}
