<?php
declare(strict_types=1);

namespace App\Resources;

use framework\Resource\Field\DateTime;
use framework\Resource\Field\Id;
use framework\Resource\Field\Integer;
use framework\Resource\Field\TinyInt;
use framework\Resource\Field\Varchar;
use framework\Resource\Resource;
use framework\Validator\MinLength;
use framework\Validator\PositiveInteger;

class Post extends Resource
{
    public function getTableName(): string
    {
        return 'post';
    }

    public function getFields(): array
    {
        return [
            'id'         => new Id(),
            'content'    => new Varchar(500),
            'created_at' => new DateTime(),
            'up_votes'   => new TinyInt(),
            'down_votes' => new Integer(),
            'views'      => new Integer(),
        ];
    }

    protected function getDefaultReadFieldsArray(): array
    {
        return [
            'id',
            'content',
            'created_at',
            'up_votes',
            'down_votes',
            'views',
        ];
    }

    protected function getEditableFieldsArray(): array
    {
        return [
            'content',
            'created_at',
            'up_votes',
            'down_votes',
            'views',
        ];
    }

    public function getFieldValidators(): array
    {
        return [
            'content'    => [
                new MinLength(30),
            ],
            'up_votes'   => [
                new PositiveInteger(),
            ],
            'down_votes' => [
                new PositiveInteger(),
            ],
            'views'      => [
                new PositiveInteger(),
            ],
        ];
    }

    public function getRelations(): array
    {
        return [];
    }
}