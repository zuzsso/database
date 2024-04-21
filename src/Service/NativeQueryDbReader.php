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
}
