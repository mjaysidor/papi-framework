<?php
declare(strict_types=1);

namespace papi\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClassGetter
{
    public static function getClasses(string $path): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $name = $file->getBasename();
                if (str_contains($name, '.php')) {
                    $namespace = self::getNamespace($file->getPathname());
                    if ($namespace) {
                        $files[] = $namespace .'\\'. str_replace('.php', '', $name);
                    }
                }

            }
        }

        return $files;
    }

    private static function getNamespace(string $file): string
    {
        $namespace = null;
        $handle = fopen($file, 'rb');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $parts = explode(' ', $line);
                    $namespace = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }

        return $namespace;
    }
}