<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use config\MigrationConfig;
use papi\CLI\ConsoleOutput;
use papi\Migrations\MigrationGetter;
use papi\Migrations\MigrationQueryBuilder;
use papi\Migrations\SchemaDiffGenerator;
use papi\Utils\PHPClassFileWriter;

if (! empty(MigrationGetter::getUnexecuted())) {
    ConsoleOutput::info(
        'There are unexecuted migrations. Either delete them, or execute by php papi/migrations/execute'
    );
    die();
}

$diffGenerator = new SchemaDiffGenerator();
$sql = (new MigrationQueryBuilder($diffGenerator))->getSqlStatements();

if (empty($sql)) {
    ConsoleOutput::info('Schema is up to date');
    die();
}

$className = "Migration_".(new \DateTime())->format('Y_m_d_h_i_s');
$writer = new PHPClassFileWriter(
    $className,
    'migrations',
    MigrationConfig::getAbsolutePath(),
    null,
    'Migration'
);
$writer->addImport('papi\Migrations\Migration');
$writer->addFunction(
    'public',
    'array',
    'getSQL',
    'return '.var_export($sql, true).';'
);
$writer->addFunction(
    'public',
    'array',
    'getMapping',
    'return '.var_export($diffGenerator->getCurrentSchema(), true).';'
);
$writer->write();

ConsoleOutput::success('Migration created!');