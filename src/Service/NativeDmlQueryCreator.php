<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Type\NamedParameterCollection;
use Database\Type\NativeDmlSqlQuery;
use Database\UseCase\CheckPdoParameterNames;
use Database\UseCase\CreateNativeDmlQuery;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

class NativeDmlQueryCreator implements CreateNativeDmlQuery
{
    private ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery;
    private CheckPdoParameterNames $checkPdoParameterNames;

    public function __construct(
        ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery,
        CheckPdoParameterNames $checkPdoParameterNames
    ) {
        $this->extractParameterNamesFromRawQuery = $extractParameterNamesFromRawQuery;
        $this->checkPdoParameterNames = $checkPdoParameterNames;
    }

    /**
     * @inheritDoc
     */
    public function fromBasicData(string $rawSql, array $pdoParams): NativeDmlSqlQuery
    {
        $parameters = new NamedParameterCollection();

        foreach ($pdoParams as $key => $value) {
            $parameters->add($this->checkPdoParameterNames, $key, (string)$value);
        }

        return new NativeDmlSqlQuery(
            $this->extractParameterNamesFromRawQuery,
            $rawSql,
            $parameters
        );
    }
}
