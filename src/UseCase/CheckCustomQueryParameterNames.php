<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\IncorrectCustomParameterSyntaxException;

interface CheckCustomQueryParameterNames
{
    public function getPdoPlaceholderRegex(): string;

    public function checkStringRepresentsParameterName(string $parameterName): bool;

    /**
     * @throws IncorrectCustomParameterSyntaxException
     */
    public function convertToStandardPdoSyntax(string $parameterName): string;

    /**
     * @throws IncorrectCustomParameterSyntaxException
     */
    public function removeEndDelimiter(string $parameterName): string;

    /**
     * @throws IncorrectCustomParameterSyntaxException
     */
    public function reinstateEndDelimiter(string $parameterName): string;
}
