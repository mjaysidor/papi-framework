<?php
declare(strict_types=1);

namespace papi\Documentation;

use config\DocumentationConfig;
use papi\Worker\Route;
use Symfony\Component\Yaml\Yaml;

class DocGenerator
{
    /**
     * @param string  $directory
     * @param Route[] $routes
     */
    public static function generateOpenAPIDocs(string $directory, array $routes): void
    {
        $doc = [];

        foreach ($routes as $route) {
            $tagExists = false;
            $tag = $route->getTag();

            foreach ($doc['tags'] ?? [] as $t) {
                if ($t['name'] === $tag) {
                    $tagExists = true;
                }
            }

            if ($tagExists === false) {
                $doc['tags'][] = [
                    'name' => $tag,
                ];
            }

            $doc['paths'][$route->getPath()][strtolower($route->getMethod())] = $route->getRouteOpenApiDoc();
        }

        if (isset($doc['paths'])) {
            ksort($doc['paths']);
        }

        $documentationArray = array_merge(
            (new DocumentationConfig())->getOpenAPIHeaders(),
            $doc
        );

        file_put_contents($directory, Yaml::dump($documentationArray, 10));
    }
}