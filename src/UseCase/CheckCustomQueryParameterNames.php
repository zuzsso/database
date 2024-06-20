<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\PdoParameternamesCheckerUnmanagedException;

interface CheckCustomQueryParameterNames
{
    public function getPdoPlaceholderRegex(): string;

    public function checkStringRepresentsParameterName(string $parameterName): bool;

    /**
     * @throws PdoParameternamesCheckerUnmanagedException
     */
    public function convertToStandardPdoSyntax(string $parameterName): string;
}
