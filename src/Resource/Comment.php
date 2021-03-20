<?php

declare(strict_types=1);

namespace App\Resource;

use papi\Resource\Field\Id;
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
            new \papi\Relation\ManyToMany(__CLASS__, \App\Resource\Post::class),
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

    public function getFieldValidators(): array
    {
        return [
        ];
    }
}
