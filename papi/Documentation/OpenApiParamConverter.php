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
            $data = [
                'name' => $filter,
                'in'   => $in,
            ];

            $filters[] = array_merge(
                $data,
                self::OPTIONS[$in]
            );
        }

        return $filters;
    }
}