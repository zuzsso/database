<?php

declare(strict_types=1);

namespace Database;

use Database\Service\NativeDmlQueryCreator;
use Database\Service\NativeSelectQueryCreator;
use Database\UseCase\CreateNativeDmlQuery;
use Database\UseCase\CreateNativeSelectQuery;
use DiManifest\AbstractDependencyInjection;
use Database\Service\DmlNativeQueryRunner;
use Database\Service\NativeQueryDbReader;
use Database\Service\ParameterNamesFromRawQueryExtractor;
use Database\Service\CustomQueryParameterNamesChecker;
use Database\Service\WhereInPdoParametrizer;
use Database\UseCase\CheckCustomQueryParameterNames;
use Database\UseCase\ExtractParameterNamesFromRawQuery;
use Database\UseCase\ParametrizeWhereInPdo;
use Database\UseCase\ReadDbNativeQuery;
use Database\UseCase\RunDmlNativeQuery;

use function DI\autowire;

class DependencyInjectionManifest extends AbstractDependencyInjection
{
    public static function getDependencies(): array
    {
        return [
            CreateNativeDmlQuery::class => autowire(NativeDmlQueryCreator::class),
            CreateNativeSelectQuery::class => autowire(NativeSelectQueryCreator::class),
            RunDmlNativeQuery::class => autowire(DmlNativeQueryRunner::class),
            ParametrizeWhereInPdo::class => autowire(WhereInPdoParametrizer::class),
            CheckCustomQueryParameterNames::class => autowire(CustomQueryParameterNamesChecker::class),
            ExtractParameterNamesFromRawQuery::class => autowire(ParameterNamesFromRawQueryExtractor::class),
            ReadDbNativeQuery::class => autowire(NativeQueryDbReader::class)
        ];
    }
}
