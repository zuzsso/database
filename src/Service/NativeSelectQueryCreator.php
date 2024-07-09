<?php

declare(strict_types=1);

namespace Database\Service;

use Database\Type\NamedParameterCollection;
use Database\Type\NativeSelectSqlQuery;
use Database\UseCase\CheckCustomQueryParameterNames;
use Database\UseCase\CreateNativeSelectQuery;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

class NativeSelectQueryCreator implements CreateNativeSelectQuery
{
    private ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery;
    private CheckCustomQueryParameterNames $checkPdoParameterNames;

    public function __construct(
        ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery,
        CheckCustomQueryParameterNames $checkPdoParameterNames
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
            if ($value === null) {
                $parameters->add($this->checkPdoParameterNames, $key, null);
            } else {
                $parameters->add($this->checkPdoParameterNames, $key, (string)$value);
            }
        }

        return new NativeSelectSqlQuery(
            $this->extractParameterNamesFromRawQuery,
            $rawSql,
            $parameters
        );
    }
}
