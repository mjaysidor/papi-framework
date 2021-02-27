<?php
declare(strict_types=1);

namespace papi\Generator;

use papi\Utils\CaseConverter;
use papi\Utils\PHPClassFileWriter;

class FileGenerator
{
    public static function generateResource(
        string $dir,
        string $name
    ): void {
        $writer = new PHPClassFileWriter(
            $name,
            'App\Resources\\'.$dir,
            'src/Resources/'.$dir,
            'Resource',
            null
        );
        $writer->addImport('papi\Resource\Resource');
        $writer->addImport('papi\Resource\Field\Id');
        $writer->addFunction(
            'public',
            'string',
            'getTableName',
            "return '".CaseConverter::camelToSnake($name)."';"
        );
        $writer->addFunction(
            'public',
            'array',
            'getFields',
            "return ['id' => new Id()];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getDefaultReadFields',
            "return ['id'];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getEditableFields',
            "return [];"
        );
        $writer->addFunction(
            'public',
            'array',
            'getFieldValidators',
            "return [];"
        );

        $writer->write();
    }
}