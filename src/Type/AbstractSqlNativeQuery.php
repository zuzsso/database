<?php

declare(strict_types=1);

namespace Database\Type;

use Database\Exception\NativeQueryDbReaderUnmanagedException;
use Database\Exception\UnconstructibleRawSqlQueryException;
use Database\UseCase\ExtractParameterNamesFromRawQuery;

abstract class AbstractSqlNativeQuery
{
    protected ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery;

    protected string $rawSql;

    protected ?NamedParameterCollection $queryParams;

    /**
     * @throws UnconstructibleRawSqlQueryException
     */
    public function __construct(
        ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery,
        string $rawSql,
        ?NamedParameterCollection $queryParams
    ) {
        $this->extractParameterNamesFromRawQuery = $extractParameterNamesFromRawQuery;
        $this->rawSql = $rawSql;
        $this->queryParams = $queryParams;

        if (strpos($rawSql, ';') !== false) {
            throw new UnconstructibleRawSqlQueryException("Found ';' in the raw text. Multiple queries not allowed");
        }

        try {
            $this->checkAllParametersInCollectionExistInQuery();
        } catch (NativeQueryDbReaderUnmanagedException $e) {
            throw new UnconstructibleRawSqlQueryException($e->getMessage(), $e->getCode(), $e);
        }
    }

    final public function getRawSql(): string
    {
        return $this->rawSql;
    }

    final public function getParams(): ?NamedParameterCollection
    {
        return $this->queryParams;
    }

    /**
     * @throws NativeQueryDbReaderUnmanagedException
     */
    private function checkAllParametersInCollectionExistInQuery(): void
    {
        $extractedParameters = $this->extractParameterNamesFromRawQuery->extract($this->rawSql);

        $extractedParametersCount = count($extractedParameters);

        $collectionCount = 0;
        if ($this->queryParams !== null) {
            $collectionCount = $this->queryParams->count();
        }

        if (($collectionCount === 0) && ($extractedParametersCount === 0)) {
            return;
        }

        if ($collectionCount !== $extractedParametersCount) {
            throw new NativeQueryDbReaderUnmanagedException(
                "Found $extractedParametersCount parameters to be bound in the raw query, but the collection has $collectionCount. Make sure that placeholders in the raw query are prefixed with ':' "
            );
        }

        foreach ($extractedParameters as $extractedParameter) {
            if (!$this->queryParams->hasParameter($extractedParameter)) {
                throw new NativeQueryDbReaderUnmanagedException(
                    "Found placeholder '$extractedParameter' in the raw query but the collection doesn't have '$extractedParameter'. Make sure you call \$myCollection->add(...,'$extractedParameter', 'your value')"
                );
            }
        }
    }
}