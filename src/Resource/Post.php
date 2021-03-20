<?php

declare(strict_types=1);

namespace App\Resource;

use papi\Resource\Field\Id;
use papi\Resource\Field\Text;
use papi\Resource\Resource;

class Post extends Resource
{
    public function getTableName(): string
    {
        return 'post';
    }

    public function getFields(): array
    {
        return [
            'id'      => new Id(),
            'content' => new Text(),
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return ['id'];
    }

    public function getEditableFields(): array
    {
        return ['content'];
    }

    public function getFieldValidators(): array
    {
        return [];
    }
}
