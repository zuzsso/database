<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\PdoParameternamesCheckerUnmanagedException;

interface ExtractParameterNamesFromRawQuery
{
    public function extract(string $sqlQuery): array;

    /**
     * @throws PdoParameternamesCheckerUnmanagedException
     */
    public function convertToStandardPdoSyntaxProxy(string $parameterName): string;
}
