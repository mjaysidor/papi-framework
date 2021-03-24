<?php

declare(strict_types=1);

namespace papi\Relation;

use Exception;
use papi\CLI\ConsoleOutput;
use papi\Generator\FileGenerator;
use ReflectionClass;

class RelationMaker
{
    public const ONE_TO_ONE   = 'OneToOne';
    public const MANY_TO_ONE  = 'ManyToOne';
    public const MANY_TO_MANY = 'ManyToMany';

    public static function makeOneToOne(string $rootResource, string $relatedResource): void
    {
        self::addRelation($rootResource, $relatedResource, self::ONE_TO_ONE);
    }

    public static function makeManyToOne(string $rootResource, string $relatedResource): void
    {
        self::addRelation($rootResource, $relatedResource, self::MANY_TO_ONE);
    }

    public static function makeManyToMany(string $rootResource, string $relatedResource): void
    {
        self::addRelation($rootResource, $relatedResource, self::MANY_TO_MANY);
        try {
            FileGenerator::generateManyToManyController($rootResource, $relatedResource);
            ConsoleOutput::success('Controller created!');
        } catch (Exception $exception) {
            ConsoleOutput::errorDie($exception->getMessage());
        }
    }

    private static function addRelation(string $rootResource, string $relatedResource, string $relationType): void
    {
        $reflector = new ReflectionClass(new $rootResource());
        $rootResourceFileName = $reflector->getFileName();

        if ($rootResourceFileName === false) {
            throw new \RuntimeException('Cannot get filename');
        }

        $data = file($rootResourceFileName);

        if ($data === false) {
            throw new \RuntimeException('Cannot open file');
        }

        $relationDefinition = "            ";
        $fieldName = '';
        if ($relationType !== self::MANY_TO_MANY) {
            $fieldName = explode('\\', $relatedResource);
            $fieldName = end($fieldName);
            $fieldName = strtolower($fieldName) . '_id';
            $relationDefinition .= "'$fieldName' => ";
        }
        $relationDefinition
            .= "new \\papi\\Relation\\$relationType(__CLASS__, \\$relatedResource::class),\n";

        if (in_array($relationDefinition, $data, true)) {
            ConsoleOutput::errorDie('Relation already exists in resource class');
        }

        foreach ($data as $key => $line) {
            if (str_contains($line, 'getFields()')) {
                $j = $key;
                while (! str_contains($data[$j], '];')) {
                    $j++;
                }
                array_splice($data, $j, 0, $relationDefinition);
            }
            if ($relationType !== self::MANY_TO_MANY) {
                if (str_contains($line, 'getDefaultSELECTFields()') || str_contains($line, 'getEditableFields()')) {
                    $j = $key;
                    while (! str_contains($data[$j], '];')) {
                        $j++;
                    }
                    array_splice($data, $j, 0, "            '$fieldName',\n");
                }
            }
        }

        file_put_contents($rootResourceFileName, implode('', $data));
    }
}
