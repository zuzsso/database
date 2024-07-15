<?php

declare(strict_types=1);

namespace Database\UseCase;

use Doctrine\DBAL\Connection;
use Database\Exception\NativeQueryDbReaderUnmanagedException;
use Database\Type\NativeSelectSqlQuery;

interface ReadDbNativeQuery
{
    /**
     * @throws NativeQueryDbReaderUnmanagedException
     */
    public function getAllRawRecords(
        Connection $connex,
        NativeSelectSqlQuery $query
    ): array;

    /**
     * @throws NativeQueryDbReaderUnmanagedException
     */
    public function getAllRawRecordsIndexedBy(
        Connection $connex,
        NativeSelectSqlQuery $query,
        string $columnName,
        bool $castToInt
    );
}
