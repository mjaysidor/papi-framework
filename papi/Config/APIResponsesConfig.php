<?php
declare(strict_types=1);

namespace papi\Config;

abstract class APIResponsesConfig
{
    public function getResponses(string $method = 'GET'): array
    {
        switch ($method) {
            case 'POST':
                return $this->getPOSTResponses();
            case 'DELETE':
                return $this->getDELETEResponses();
            case 'PUT':
                return $this->getPUTResponses();
        }

        return $this->getGETResponses();
    }

    abstract public function getGETResponses(): array;

    abstract public function getPOSTResponses(): array;

    abstract public function getPUTResponses(): array;

    abstract public function getDELETEResponses(): array;
}