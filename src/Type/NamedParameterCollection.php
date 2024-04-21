<?php

declare(strict_types=1);

namespace Database\Type;

use Database\Exception\IncorrectQueryParametrizationException;
use TypedCollection\AbstractStringAssociativeCollection;
use TypedCollection\Exception\KeyAlreadyExistsException;
use Database\UseCase\CheckPdoParameterNames;

class NamedParameterCollection extends AbstractStringAssociativeCollection
{
    /**
     * @throws IncorrectQueryParametrizationException
     */
    public function add(
        CheckPdoParameterNames $checkPdoParameterNames,
        string $parameterName,
        string $parameterValue
    ): void {
        if (!$checkPdoParameterNames->checkStringRepresentsParameterName($parameterName)) {
            throw new IncorrectQueryParametrizationException(
                "Parameter name '$parameterName' not in expected format. Does it start with colon?"
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
