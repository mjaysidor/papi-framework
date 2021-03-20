<?php

declare(strict_types=1);

namespace papi\Config;

abstract class APIResponsesConfig
{
    public function getResponses(string $method = 'GET'): array
    {
        switch ($method) {
            case 'GET':
                return $this->getGETResponses();
            case 'POST':
                return $this->getPOSTResponses();
            case 'DELETE':
                return $this->getDELETEResponses();
            case 'PUT':
                return $this->getPUTResponses();
        }

        return [];
    }

    abstract public function getGETResponses(): array;

    abstract public function getPOSTResponses(): array;

    abstract public function getPUTResponses(): array;

    abstract public function getDELETEResponses(): array;

    abstract public function getAuthResponses(): array;
}
