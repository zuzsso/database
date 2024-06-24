<?php

declare(strict_types=1);

namespace Database\Type;

use Database\Exception\ParametrizedPdoArrayException;
use Database\UseCase\CheckCustomQueryParameterNames;

class ParametrizedWhereInArray
{
    private array $parameterNames = [];
    private array $parameterAssociation = [];

    /**
     * @throws ParametrizedPdoArrayException
     * @noinspection PhpUnused
     */
    public function addParameter(
        CheckCustomQueryParameterNames $checkCustomQueryParameterNames,
        string $parameterName,
        string $parameterValue
    ): void {
        $checkCustomQueryParameterNames->checkStringRepresentsParameterName($parameterName);

        if (in_array($parameterName, $this->parameterNames, true)) {
            throw new ParametrizedPdoArrayException("Duplicated parameter: $parameterName");
        }

        $sanitizedParameterValue = trim($parameterValue);

        if ($sanitizedParameterValue === '') {
            throw new ParametrizedPdoArrayException('Parameter value is empty string');
        }

        $this->parameterNames[] = $parameterName;
        $this->parameterAssociation[$parameterName] = $parameterValue;
    }

    /**
     * @throws ParametrizedPdoArrayException
     */
    public function getParameterNames(): array
    {
        if (count($this->parameterNames) === 0) {
            throw new ParametrizedPdoArrayException('No parameters');
        }

        return $this->parameterNames;
    }

    /**
     * @throws ParametrizedPdoArrayException
     * @noinspection PhpUnused
     */
    public function getParameterNamesAsString(): string
    {
        $names = $this->getParameterNames();

        return implode(',', $names);
    }

    /**
     * @throws ParametrizedPdoArrayException
     * @noinspection PhpUnused
     */
    public function getParameterAssociation(): array
    {
        if (count($this->parameterAssociation) === 0) {
            throw new ParametrizedPdoArrayException('No parameters');
        }

        return $this->parameterAssociation;
    }
}
