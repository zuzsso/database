<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Exception\NativeQueryDbReaderUnmanagedException;
use Database\Type\ParametrizedPdoArray;
use Database\UseCase\CheckCustomQueryParameterNames;
use Database\UseCase\ParametrizeWhereInPdo;

class WhereInPdoParametrizer implements ParametrizeWhereInPdo
{
    private CheckCustomQueryParameterNames $checkPdoParameterNames;

    public function __construct(CheckCustomQueryParameterNames $checkPdoParameterNames)
    {
        $this->checkPdoParameterNames = $checkPdoParameterNames;
    }

    /**
     * @inheritDoc
     */
    public function parametrize(string $prefix, array $values): ParametrizedPdoArray
    {
        if (!$this->checkPdoParameterNames->checkStringRepresentsParameterName($prefix)) {
            throw new NativeQueryDbReaderUnmanagedException(
                "Prefix '$prefix' doesn't seem to be a valid name for a PDO parameter"
            );
        }

        $counter = 0;

        $result = new ParametrizedPdoArray();

        foreach ($values as $v) {
            $thisParameterName = "${prefix}_$counter";
            $result->addParameter($thisParameterName, $v);

            $counter++;
        }

        return $result;
    }
}
