<?php
declare(strict_types=1);

namespace papi\Documentation;

use config\APIResponses;
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
                $resourceName = $data['resourceName'] ?? 'custom';
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

                if (isset($data['body'])) {
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
                        'responses' => $data['responses'] ?? APIResponses::getResponses($method),
                    ],
                    $requestBody,
                    isset($data['parameters']) && $data['parameters'] ? [
                        'parameters' => $data['parameters'] ?? [],
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