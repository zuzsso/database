<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Exception\IncorrectCustomParameterSyntaxException;
use Database\UseCase\CheckCustomQueryParameterNames;

class CustomQueryParameterNamesChecker implements CheckCustomQueryParameterNames
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

    /**
     * @inheritDoc
     */
    public function convertToStandardPdoSyntax(string $parameterName): string
    {
        if ($this->checkStringRepresentsParameterName($parameterName)) {
            $result = $parameterName;
            return str_replace(array('-:', ':+'), array(':', ''), $result);
        }

        throw new IncorrectCustomParameterSyntaxException(
            "Parameter syntax not recognized. It should be '-:myParameterName:+'"
        );
    }

    /**
     * @inheritDoc
     */
    public function removeEndDelimiter(string $parameterName): string
    {
        if (!$this->checkStringRepresentsParameterName($parameterName)) {
            throw new IncorrectCustomParameterSyntaxException(
                "Parameter syntax not recognized. It should be '-:myParameterName:+'"
            );
        }

        return str_replace(':+', '', $parameterName);
    }

    /**
     * @inheritDoc
     */
    public function reinstateEndDelimiter(string $parameterName): string
    {
        $aux = $parameterName . ':+';

        if (!$this->checkStringRepresentsParameterName($aux)) {
            throw new IncorrectCustomParameterSyntaxException(
                "Parameter syntax not recognized. It should be '-:myParameterName:+', but got '$aux'"
            );
        }

        return $aux;
    }
}
