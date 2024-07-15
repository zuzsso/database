<?php

declare(strict_types=1);

namespace Database\Service;

use Doctrine\DBAL\Connection;
use Throwable;
use Database\Exception\NativeQueryDbReaderUnmanagedException;
use Database\Type\NativeSelectSqlQuery;
use Database\UseCase\ReadDbNativeQuery;

class NativeQueryDbReader extends AbstractNativeQueryRunner implements ReadDbNativeQuery
{
    /**
     * @inheritDoc
     */
    public function getAllRawRecords(
        Connection $connex,
        NativeSelectSqlQuery $query
    ): array {
        try {
            $nativeSqlQuery = $query->getRawSql();
            $queryParameters = $query->getParams();
            $stm = $connex->prepare($nativeSqlQuery);

            if ($queryParameters !== null) {
                $this->bindParameters($stm, $queryParameters);
            }

            /** @noinspection OneTimeUseVariablesInspection */
            $result = $stm->executeQuery();

            return $result->fetchAllAssociative();
        } catch (Throwable $t) {
            throw new NativeQueryDbReaderUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAllRawRecordsIndexedBy(
        Connection $connex,
        NativeSelectSqlQuery $query,
        string $columnName
    ): array {
        if (trim($columnName) === '') {
            throw new NativeQueryDbReaderUnmanagedException("Column name is empty");
        }

        $cleaned = trim($columnName);

        if ($cleaned !== $columnName) {
            throw new NativeQueryDbReaderUnmanagedException("Column name contains leading or trailing whitespaces");
        }

        $raws = $this->getAllRawRecords($connex, $query);


        $result = [];

        foreach ($raws as $i => $raw) {
            $index = $raw[$columnName] ?? null;

            if ($index === null) {
                throw new NativeQueryDbReaderUnmanagedException(
                    "Not valid index found at row $i (zero-based) and column '$cleaned': null"
                );
            }

            $indexClean = trim($index);

            if ($indexClean === '') {
                throw new NativeQueryDbReaderUnmanagedException(
                    "Not valid index found at row $i (zero-based) and column '$cleaned': empty"
                );
            }

            if ($indexClean !== $index) {
                throw new NativeQueryDbReaderUnmanagedException(
                    "Not valid index found at row $i (zero-based) and column '$cleaned': trailing or leading spaces"
                );
            }

            if (array_key_exists($indexClean, $result)) {
                throw new NativeQueryDbReaderUnmanagedException(
                    "Not valid index found at row $i (zero-based) and column '$cleaned': repeated index"
                );
            }

            $result[$cleaned] = $raw;
        }

        return $result;
    }
}
