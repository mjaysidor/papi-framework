<?php
declare(strict_types=1);

namespace framework\Documentation;

use config\DocumentationConfig;
use Symfony\Component\Yaml\Yaml;

class DocGenerator
{
    public static function generateOpenAPIDocs(string $directory, array $routes): void
    {
        $doc = [];

        foreach ($routes as $method => $route) {
            foreach ($route as $data) {
                $path = $data[0];
                $resourceName = explode('/', $path)[1];
                $tagExists = false;

                foreach ($doc['tags'] ?? [] as $tag) {
                    if ($tag['name'] === $resourceName) {
                        $tagExists = true;
                    }
                }

                if (! $tagExists) {
                    $doc['tags'][] = [
                        'name' => $resourceName,
                    ];
                }

                $requestBody = [];

                if ($data['body']) {
                    $requestBody = [
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type'       => 'object',
                                        'properties' => $data['body'],
                                    ],
                                ],
                            ],
                        ],
                    ];
                }

                $doc['paths'][$path][strtolower($method)] = array_merge(
                    [
                        'tags'      => [$resourceName],
                        'responses' => $data['responses'],
                    ],
                    $requestBody,
                    $data['parameters'] ? [
                        'parameters' => $data['parameters'],
                    ] : []
                );
            }
        }

        ksort($doc['paths']);

        $documentationArray = array_merge(
            (new DocumentationConfig())->getOpenAPIHeaders(),
            $doc
        );

        file_put_contents($directory, Yaml::dump($documentationArray, 10));
    }
}