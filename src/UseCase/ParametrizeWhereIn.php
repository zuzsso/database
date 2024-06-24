<?php

declare(strict_types=1);

namespace Database\UseCase;

use Database\Exception\NativeQueryDbReaderUnmanagedException;
use Database\Exception\ParametrizedPdoArrayException;
use Database\Type\ParametrizedWhereInArray;

interface ParametrizeWhereIn
{
    /**
     * @throws ParametrizedPdoArrayException
     * @throws NativeQueryDbReaderUnmanagedException
     */
    public function parametrize(string $prefix, array $values): ParametrizedWhereInArray;
}
