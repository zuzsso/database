<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\IncorrectCustomParameterSyntaxException;
use Database\Exception\ParametrizedPdoArrayException;
use Database\Type\ParametrizedWhereInArray;

interface ParametrizeWhereIn
{
    /**
     * @throws IncorrectCustomParameterSyntaxException
     * @throws ParametrizedPdoArrayException
     * @deprecated
     * @see \Database\UseCase\ParametrizeWhereIn::simpleParametrize
     */
    public function parametrize(
        CheckCustomQueryParameterNames $checkCustomQueryParameterNames,
        string $prefix,
        array $values
    ): ParametrizedWhereInArray;

    /**
     * @throws IncorrectCustomParameterSyntaxException
     * @throws ParametrizedPdoArrayException
     */
    public function simpleParametrize(
        string $prefix,
        array $values
    ): ParametrizedWhereInArray;
}
