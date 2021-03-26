<?php

declare(strict_types=1);

namespace papi\Utils;

use Throwable;

/**
 * Handles app error logging
 */
class ErrorLogger
{
    /**
     * Save error info to project var/log/error.log
     *
     * @param Throwable $exception
     */
    public static function logError(Throwable $exception): void
    {
        file_put_contents(
            'var/log/error.log',
            '[' . (new \DateTime())->format('Y-m-d H:i:s') . ']' .
            ': ' . $exception->getMessage() .
            ' @ ' . $exception->getFile() .
            ': ' . $exception->getLine() .
            PHP_EOL,
            FILE_APPEND
        );
    }
}
