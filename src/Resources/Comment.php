<?php
declare(strict_types=1);

namespace App\Resources;

use framework\Relation\ManyToMany;
use framework\Resource\Field\DateTime;
use framework\Resource\Field\Id;
use framework\Resource\Field\SmallInt;
use framework\Resource\Field\Varchar;
use framework\Resource\Resource;
use framework\Validator\MinLength;
use framework\Validator\PositiveInteger;

class Comment extends Resource
{
    public function getTableName(): string
    {
        return 'comment';
    }

    public function getFields(): array
    {
        return [
            'id'         => new Id(),
            'content'    => new Varchar(500),
            'created_at' => new DateTime(),
            'up_votes'   => new SmallInt(),
            'down_votes' => new SmallInt(),
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
        ];
    }

    protected function getEditableFieldsArray(): array
    {
        return [
            'content',
            'up_votes',
            'down_votes',
        ];
    }

    public function getFieldValidators(): array
    {
        return [
            'content'    => [
                new MinLength(5),
            ],
            'up_votes'   => [
                new PositiveInteger(),
            ],
            'down_votes' => [
                new PositiveInteger(),
            ],
        ];
    }

    public function getRelations(): array
    {
        return [
            new ManyToMany(__CLASS__, Post::class),
        ];
    }
}