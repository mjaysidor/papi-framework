<?php
declare(strict_types=1);
namespace App\Callbacks;

use JsonException;
use papi\Callbacks\PreExecutionBodyModifier;

class AddRelationUserId implements PreExecutionBodyModifier
{
    private string $fieldName;

    private string $userId;

    public function __construct(
        string $userId,
        string $fieldName = 'app_user_id',
    ) {
        $this->userId = $userId;
        $this->fieldName = $fieldName;
    }

    public function modify(array &$body): void
    {
        $body[$this->fieldName] = (int)$this->userId;
    }
}
