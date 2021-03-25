<?php

declare(strict_types=1);

namespace papi\Documentation;

/**
 * Converts provided endpoint route parameters (ex. /"id") to OpenAPI documentation
 */
class RouteParametersDocGenerator
{
    public const PATH  = 'path';
    public const QUERY = 'query';
    public const OPTIONS
                       = [
            self::PATH  => [
                'required' => true,
                'schema'   => [
                    'type' => 'integer',
                ],
            ],
            self::QUERY => [
                'schema' => [
                    'type' => 'string',
                ],
            ],
        ];

    /**
     * Generate OpenAPI documentation
     *
     * @param array  $parameterNames
     * @param string $in
     *
     * @return array
     */
    public static function generate(
        array $parameterNames,
        string $in = self::PATH
    ): array {
        $doc = [];
        foreach ($parameterNames as $name) {
            $doc[] = array_merge(
                [
                    'name' => $name,
                    'in'   => $in,
                ],
                self::OPTIONS[$in]
            );
        }

        return $doc;
    }
}
