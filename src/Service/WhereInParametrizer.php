<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Exception\IncorrectCustomParameterSyntaxException;
use Database\Type\ParametrizedWhereInArray;
use Database\UseCase\CheckCustomQueryParameterNames;
use Database\UseCase\ParametrizeWhereIn;

class WhereInParametrizer implements ParametrizeWhereIn
{
    private CheckCustomQueryParameterNames $checkPdoParameterNames;

    public function __construct(CheckCustomQueryParameterNames $checkCustomQueryParameterNames)
    {
        $this->checkPdoParameterNames = $checkCustomQueryParameterNames;
    }

    public function simpleParametrize(string $prefix, array $values): ParametrizedWhereInArray
    {
        if (!$this->checkPdoParameterNames->checkStringRepresentsParameterName($prefix)) {
            throw new IncorrectCustomParameterSyntaxException(
                "Prefix '$prefix' doesn't seem to be a valid name for a PDO parameter"
            );
        }

        $counter = 0;

        $result = new ParametrizedWhereInArray();

        $prefixModified = $this->checkPdoParameterNames->removeEndDelimiter($prefix);

        foreach ($values as $v) {
            $thisParameterName = "${prefixModified}_$counter";

            $thisParameterName = $this->checkPdoParameterNames->reinstateEndDelimiter($thisParameterName);

            $result->addParameter($this->checkPdoParameterNames, $thisParameterName, (string)$v);

            $counter++;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function parametrize(
        CheckCustomQueryParameterNames $checkCustomQueryParameterNames,
        string $prefix,
        array $values
    ): ParametrizedWhereInArray {
        return $this->simpleParametrize($prefix, $values);
    }
}
