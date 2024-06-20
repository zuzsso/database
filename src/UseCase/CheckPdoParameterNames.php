<?php

declare(strict_types=1);

namespace Database\UseCase;

interface CheckPdoParameterNames
{
    public function getPdoPlaceholderRegex(): string;

    public function checkStringRepresentsParameterName(string $parameterName): bool;
}
