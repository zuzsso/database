<?php

declare(strict_types=1);

namespace Database\Type;

use Database\Exception\IncorrectQueryParametrizationException;
use Database\Exception\NamedParameterCollectionUnmanagedException;
use Database\UseCase\ExtractParameterNamesFromRawQuery;
use Throwable;
use TypedCollection\AbstractStringAssociativeCollection;
use TypedCollection\Exception\KeyAlreadyExistsException;
use Database\UseCase\CheckCustomQueryParameterNames;

class NamedParameterCollection extends AbstractStringAssociativeCollection
{
    /**
     * @throws IncorrectQueryParametrizationException
     */
    public function add(
        CheckCustomQueryParameterNames $checkPdoParameterNames,
        string $parameterName,
        string $parameterValue
    ): void {
        if (!$checkPdoParameterNames->checkStringRepresentsParameterName($parameterName)) {
            throw new IncorrectQueryParametrizationException(
                "Parameter name '$parameterName' not in expected format, as in '-:paramName:+'"
            );
        }

        if (trim($parameterValue) === '') {
            throw new IncorrectQueryParametrizationException(
                "Parameter name '$parameterName' is associated with NULL or empty string, so no need to be parametrized"
            );
        }

        try {
            $this->addStringKeyUntyped($parameterName, $parameterValue);
        } catch (KeyAlreadyExistsException $e) {
            throw new IncorrectQueryParametrizationException(
                "Parameter name '$parameterName' is already in this collection"
            );
        }
    }

    /**
     * @throws NamedParameterCollectionUnmanagedException
     */
    public function transformToPdoSyntax(
        CheckCustomQueryParameterNames $checkPdoParameterNames,
        string $parameter
    ): string {
        try {
            // Check if the parameter exists in this collection. If it doesn't, it will raise an exception
            $this->getByStringKey($parameter);

            return $checkPdoParameterNames->convertToStandardPdoSyntax($parameter);
        } catch (Throwable $t) {
            throw new NamedParameterCollectionUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }

    /**
     * @throws NamedParameterCollectionUnmanagedException
     */
    public function transformToPdoSyntaxProxy(
        ExtractParameterNamesFromRawQuery $extractParameterNamesFromRawQuery,
        string $parameter
    ): string {
        try {
            // Check if the parameter exists in this collection. If it doesn't, it will raise an exception
            $this->getByStringKey($parameter);

            return $extractParameterNamesFromRawQuery->convertToStandardPdoSyntaxProxy($parameter);
        } catch (Throwable $t) {
            throw new NamedParameterCollectionUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByStringKey(
        string $key
    ): string {
        return $this->getByStringKeyUntyped($key);
    }

    /**
     * @inheritDoc
     */
    public function getByNumericOffset(
        int $offset
    ): string {
        return $this->getByNumericOffsetUntyped($offset);
    }

    /**
     * @inheritDoc
     */
    public function current(): string
    {
        return $this->currentUntyped();
    }

    public function hasParameter(string $parameterName): bool
    {
        return $this->checkStringKeyExists($parameterName);
    }
}
