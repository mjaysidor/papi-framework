<?php
declare(strict_types=1);

namespace papi\Documentation;

class OpenApiParamConverter
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

    public static function convertArrayToDoc(array $params, string $in = self::PATH): array
    {
        $filters = [];
        foreach ($params as $filter) {
            $filters[] = array_merge(
                [
                    'name' => $filter,
                    'in'   => $in,
                ],
                self::OPTIONS[$in]
            );
        }

        return $filters;
    }
}