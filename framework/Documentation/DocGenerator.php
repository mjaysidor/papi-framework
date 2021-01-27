<?php
declare(strict_types=1);

namespace framework\Documentation;

use config\DocumentationConfig;
use framework\Resource\Field\Id;
use Symfony\Component\Yaml\Yaml;

class DocGenerator
{
    public static function generateSwaggerDoc(string $directory, array $routes): void
    {
        $doc = [];

        foreach ($routes as $method => $route) {
            foreach ($route as $data) {
                $path = $data[0];
                $tagName = explode('/', $path)[1];
                $tagExists = false;

                foreach ($doc['tags'] ?? [] as $tag) {
                    if ($tag['name'] === $tagName) {
                        $tagExists = true;
                    }
                }

                if (! $tagExists) {
                    $doc['tags'][] = [
                        'name' => $tagName,
                    ];
                }

                $requestBody = [];
                $paramsArray = [];
                $tagsAndResponses = [
                    'tags'      => [$tagName],
                    'responses' => $data[2],
                ];

                if ($data[3]) {
                    $requestBody = [
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type'       => 'object',
                                        'properties' => $data[3],
                                    ],
                                ],
                            ],
                        ],
                    ];
                }

                $params = $data[4];

                if ($params) {
                    foreach ($params as $param) {
                        $paramsArray['parameters'][] = [
                            'name'     => $param,
                            'in'       => 'path',
                            'required' => true,
                            'schema'   => [
                                'type' => (new Id())->getPHPTypeName(),
                            ],
                        ];
                    }
                }

                $doc['paths'][$path][strtolower($method)] = array_merge($tagsAndResponses, $requestBody, $paramsArray);
            }
        }

        ksort($doc['paths']);

        $documentationArray = array_merge(
            DocumentationConfig::getHeaders(),
            $doc
        );

        file_put_contents($directory, Yaml::dump($documentationArray, 10));
    }
}