<?php
declare(strict_types=1);

namespace papi\CLI\Commands\Make;

use papi\CLI\Command;
use papi\CLI\ConsoleInput;
use papi\CLI\ConsoleOutput;
use papi\Config\ProjectStructure;
use papi\Relation\RelationMaker;
use papi\Utils\ClassGetter;

/**
 * Creates a relation between resources
 */
class MakeRelation implements Command
{
    public function getCommand(): string
    {
        return 'make:relation';
    }

    public function getDescription(): string
    {
        return 'Creates a relation between resources';
    }

    public function execute(): void
    {
        ConsoleOutput::success('Relation type');
        $relationType = ConsoleInput::getInputFromChoices('Types of relation', ['OneToOne', 'ManyToOne', 'ManyToMany']);
        $resources = ClassGetter::getClasses(ProjectStructure::getResourcesPath());
        ConsoleOutput::success('Root resource');
        $rootResource = ConsoleInput::getInputFromChoices('Available resources:', $resources);
        ConsoleOutput::success('Related resource');
        $relatedResource = ConsoleInput::getInputFromChoices('Available resources:', $resources);

        switch ($relationType) {
            case 'OneToOne':
                RelationMaker::makeOneToOne($rootResource, $relatedResource);
                break;
            case 'ManyToOne':
                RelationMaker::makeManyToOne($rootResource, $relatedResource);
                break;
            case 'ManyToMany':
                RelationMaker::makeManyToMany($rootResource, $relatedResource);
                break;
        }

        ConsoleOutput::success('Relation created!');
    }
}
