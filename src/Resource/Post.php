<?php

declare(strict_types=1);

namespace App\Resource;

use papi\Relation\ManyToOne;
use papi\Resource\Field\Id;
use papi\Resource\Field\Text;
use papi\Resource\Resource;
use papi\Validator\Email;
use papi\Validator\MinLength;
use papi\Validator\NotBlank;

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
            'content'    => new Text(),
            'comment_id' => new ManyToOne(__CLASS__, Comment::class),
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
            'comment_id',
        ];
    }

    public function getEditableFields(): array
    {
        return [
            'content',
            'comment_id',
        ];
    }

    public function getPUTValidators(): array
    {
        return [
            [
                'content' => [
                    new MinLength(10),
                ],
            ],
        ];
    }

    public function getPOSTValidators(): array
    {
        return [
            'content' => [
                new MinLength(10),
                new NotBlank(),
            ],
            'email'   => [
                new Email(),
            ],
        ];
    }
}
