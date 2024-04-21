<?php

declare(strict_types=1);

namespace Database\UseCase;

use Doctrine\DBAL\Connection;
use Database\Exception\DmlNativeQueryRunnerUnmanagedException;
use Database\Type\NativeDmlSqlQuery;

interface RunDmlNativeQuery
{
    /**
     * @throws DmlNativeQueryRunnerUnmanagedException
     */
    public function executeDml(
        Connection $connex,
        NativeDmlSqlQuery $query
    ): void;
}
