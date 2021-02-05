<?php
declare(strict_types=1);

namespace App\Resources;

use papi\Relation\ManyToMany;
use papi\Resource\Field\Id;
use papi\Resource\Field\SmallInt;
use papi\Resource\Field\Timestamp;
use papi\Resource\Field\Varchar;
use papi\Resource\Resource;
use papi\Validator\MinLength;
use papi\Validator\PositiveInteger;

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
            'created_at' => new Timestamp(),
            'up_votes'   => new SmallInt(),
            'down_votes' => new SmallInt(),
            new ManyToMany(__CLASS__, Post::class),
        ];
    }

    public function getDefaultReadFields(): array
    {
        return [
            'id',
            'content',
            'created_at',
            'up_votes',
            'down_votes',
        ];
    }

    public function getEditableFields(): array
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
}