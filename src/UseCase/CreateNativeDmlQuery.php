<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\IncorrectQueryParametrizationException;
use Database\Exception\UnconstructibleRawSqlQueryException;
use Database\Type\NativeDmlSqlQuery;

interface CreateNativeDmlQuery
{
    /**
     * @throws IncorrectQueryParametrizationException
     * @throws UnconstructibleRawSqlQueryException
     */
    public function fromBasicData(string $rawSql, array $pdoParams): NativeDmlSqlQuery;
}
