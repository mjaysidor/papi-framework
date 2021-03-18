<?php
declare(strict_types=1);

namespace papi\Documentation;

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
