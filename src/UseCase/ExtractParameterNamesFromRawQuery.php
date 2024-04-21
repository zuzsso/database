<?php

declare(strict_types=1);

namespace Database\UseCase;

interface ExtractParameterNamesFromRawQuery
{
    public function extract(string $sqlQuery): array;
}
