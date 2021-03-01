<?php
declare(strict_types=1);

namespace papi\Exception;

use JetBrains\PhpStorm\Pure;

class NotImplementedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Feature has not been implemented yet!');
    }
}