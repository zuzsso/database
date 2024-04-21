<?php

declare(strict_types=1);

namespace Database\Service;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Statement;
use Database\Type\NamedParameterCollection;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

abstract class AbstractNativeQueryRunner
{
    protected ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery;

    public function __construct(ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery)
    {
        $this->extractParameterNamesFromRawQuery = $extractParameterNamesFromRawQuery;
    }

    /**
     * @throws Exception
     */
    protected function bindParameters(Statement $stm, NamedParameterCollection $queryParameters): void
    {
        foreach ($queryParameters as $parameterName => $parameterValue) {
            // Parameter value as string by default
            $stm->bindValue($parameterName, $parameterValue);
        }
    }
}
