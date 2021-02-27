<?php
declare(strict_types=1);

namespace App\Resource;

use papi\Resource\Field\Id;
use papi\Resource\Field\Integer;
use papi\Resource\Field\SmallInt;
use papi\Resource\Field\Timestamp;
use papi\Resource\Field\Varchar;
use papi\Resource\Resource;
use papi\Validator\MinLength;
use papi\Validator\PositiveInteger;

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
            'created_at' => new Timestamp(),
            'up_votes'   => new SmallInt(),
            'down_votes' => new Integer(),
            'views'      => new Integer(),
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
            'views',
        ];
    }

    public function getEditableFields(): array
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
}