<?php

declare(strict_types=1);

namespace App\Resource;

use papi\Relation\ManyToMany;
use papi\Resource\Field\Id;
use papi\Resource\Field\Varchar;
use papi\Resource\Resource;

class Comment extends Resource
{
    public function getTableName(): string
    {
        return 'comment';
    }

    public function getFields(): array
    {
        return [
            'id'      => new Id(),
            'content'      => new Varchar(100),
            new ManyToMany(__CLASS__, Post::class),
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
        ];
    }

    public function getEditableFields(): array
    {
        return [
        ];
    }

    public function getPUTValidators(): array
    {
        return [
        ];
    }

    public function getPOSTValidators(): array
    {
        return [
        ];
    }
}
