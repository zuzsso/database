<?php

declare(strict_types=1);

namespace Database\Service;

use Database\UseCase\CheckPdoParameterNames;

class PdoParameterNamesChecker implements CheckPdoParameterNames
{
    public function getPdoPlaceholderRegex(bool $anyPosition = true): string
    {
        $base = '-:\w+:\+';

        if ($anyPosition) {
            return "/$base/";
        }

        return "/^$base$/";
    }

    public function checkStringRepresentsParameterName(string $parameterName): bool
    {
        preg_match_all($this->getPdoPlaceholderRegex(false), $parameterName, $matches);

        if (count($matches) === 0) {
            return false;
        }

        return count($matches[0]) === 1;
    }
}
