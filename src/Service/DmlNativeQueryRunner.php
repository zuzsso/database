<?php

declare(strict_types=1);

namespace Database\Service;

use Doctrine\DBAL\Connection;
use Throwable;
use Database\Exception\DmlNativeQueryRunnerUnmanagedException;
use Database\Type\NativeDmlSqlQuery;
use Database\UseCase\RunDmlNativeQuery;

class DmlNativeQueryRunner extends AbstractNativeQueryRunner implements RunDmlNativeQuery
{
    /**
     * @inheritDoc
     */
    public function executeDml(
        Connection $connex,
        NativeDmlSqlQuery $query
    ): void {
        try {
            $stm = $connex->prepare($query->getRawSql());

            if ($query->getParams() !== null) {
                $this->bindParameters($stm, $query->getParams());
            }

            $stm->executeQuery();
        } catch (Throwable $t) {
            throw new DmlNativeQueryRunnerUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }
}
