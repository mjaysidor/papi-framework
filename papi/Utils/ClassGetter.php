<?php

declare(strict_types=1);

namespace papi\Utils;

use Exception;
use papi\CLI\ConsoleOutput;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Returns PHP classes inside provided directory
 */
class ClassGetter
{
    /**
     * Get PHP classes inside provided directory
     *
     * @param string $path
     *
     * @return array
     */
    public static function getClasses(string $path): array
    {
        $files = [];
        $iterator = [];
        try {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        } catch (Exception $e) {
            ConsoleOutput::errorDie($e->getMessage());
        }

        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $name = $file->getBasename();
                if (str_contains($name, '.php') && $namespace = self::getNamespace($file->getPathname())) {
                    $files[] = $namespace . '\\' . str_replace('.php', '', $name);
                }
            }
        }

        return $files;
    }

    /**
     * Get namespace of file based on pathname
     *
     * @param string $pathname
     *
     * @return string|null
     */
    private static function getNamespace(string $pathname): ?string
    {
        $namespace = null;
        $handle = fopen($pathname, 'rb');
        if ($handle !== false) {
            while (($line = fgets($handle)) !== false) {
                if (str_starts_with($line, 'namespace')) {
                    $namespace = str_replace([';', PHP_EOL], '', explode(' ', $line)[1]);
                    break;
                }
            }
            fclose($handle);
        }

        return $namespace;
    }
}
