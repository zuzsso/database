<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\IncorrectQueryParametrizationException;
use Database\Exception\UnconstructibleRawSqlQueryException;
use Database\Type\NativeSelectSqlQuery;

interface CreateNativeSelectQuery
{
    /**
     * @throws IncorrectQueryParametrizationException
     * @throws UnconstructibleRawSqlQueryException
     */
    public function fromBasicData(string $rawSql, array $pdoParams): NativeSelectSqlQuery;
}
