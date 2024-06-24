<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Exception\AbstractNativeQueryRunnerUnmanagedException;
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
     * @throws AbstractNativeQueryRunnerUnmanagedException
     */
    protected function bindParameters(Statement $stm, NamedParameterCollection $queryParameters): void
    {
        try {
            foreach ($queryParameters as $parameterName => $parameterValue) {
                // Parameter value as string by default
                $standardSyntax =
                    $this
                        ->extractParameterNamesFromRawQuery
                        ->convertToStandardPdoSyntaxProxy($parameterName);
                $stm->bindValue($standardSyntax, $parameterValue);
            }
        } catch (\Throwable $t) {
            throw new AbstractNativeQueryRunnerUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }
}
