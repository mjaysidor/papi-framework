<?php

declare(strict_types=1);

namespace papi\Callbacks;

/**
 * Adds field 'roles' to request body (default value ['ROLE_USER'])
 */
class AddRole implements PreExecutionBodyModifier
{
    private string $fieldName;

    private array $roles;

    public function __construct(
        string $fieldName = 'roles',
        array $roles = ['ROLE_USER'],
    ) {
        $this->fieldName = $fieldName;
        $this->roles = $roles;
    }

    public function modify(array &$body): void
    {
        $body[$this->fieldName] = json_encode($this->roles, JSON_THROW_ON_ERROR);
    }
}
