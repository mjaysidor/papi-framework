<?php
declare(strict_types=1);

namespace App\Resource\Auth;

use papi\Resource\Resource;
use papi\Validator\NotBlank;
use papi\Resource\Field\Varchar;
use papi\Resource\Field\Id;

class User extends Resource
{
    public function getTableName(): string
    {
        return 'users';
    }

    public function getFields(): array
    {
        return [
            'id' => new Id(),
            'username' => new Varchar(30, 'unique'),
            'roles' => new Varchar(100),
            'password' => new Varchar(110)
        ];
    }

    public function getDefaultSELECTFields(): array
    {
        return [
            'id',
            'username',
            'roles'
        ];
    }

    public function getEditableFields(): array
    {
        return [
            'username',
            'password',
            'roles'
        ];
    }

    public function getFieldValidators(): array
    {
        return [
            'username' => [new NotBlank()],
            'password' => [new NotBlank()],
        ];
    }

    public function getRoles(): array
    {
        return [
            'ROLE_ADMIN',
            'ROLE_USER',
        ];
    }
}
