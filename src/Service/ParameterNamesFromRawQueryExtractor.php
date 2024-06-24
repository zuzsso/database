<?php

declare(strict_types=1);

namespace Database\Service;

use Database\UseCase\CheckCustomQueryParameterNames;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

class ParameterNamesFromRawQueryExtractor implements ExtractParameterNamesFromRawQuery
{
    private CheckCustomQueryParameterNames $checkPdoParameterNames;

    public function __construct(CheckCustomQueryParameterNames $checkPdoParameterNames)
    {
        $this->checkPdoParameterNames = $checkPdoParameterNames;
    }

    public function extract(string $sqlQuery): array
    {
        $regex = $this->checkPdoParameterNames->getPdoPlaceholderRegex();

        preg_match_all($regex, $sqlQuery, $matches);

        $result = [];

        if (count($matches) > 0) {
            $occurrences = $matches[0];

            foreach ($occurrences as $parameterName) {
                $result[] = $parameterName;
            }
        }

        return ($result);
    }

    /**
     * @inheritDoc
     */
    public function convertToStandardPdoSyntaxProxy(string $parameterName): string
    {
        return $this->checkPdoParameterNames->convertToStandardPdoSyntax($parameterName);
    }
}
