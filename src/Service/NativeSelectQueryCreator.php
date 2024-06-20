<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Type\NamedParameterCollection;
use Database\Type\NativeSelectSqlQuery;
use Database\UseCase\CheckPdoParameterNames;
use Database\UseCase\CreateNativeSelectQuery;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

class NativeSelectQueryCreator implements CreateNativeSelectQuery
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
    public function fromBasicData(string $rawSql, array $pdoParams): NativeSelectSqlQuery
    {
        $parameters = new NamedParameterCollection();

        foreach ($pdoParams as $key => $value) {
            $parameters->add($this->checkPdoParameterNames, $key, (string)$value);
        }

        return new NativeSelectSqlQuery(
            $this->extractParameterNamesFromRawQuery,
            $rawSql,
            $parameters
        );
    }
}
