<?php

declare(strict_types=1);

namespace Database;

use DiManifest\AbstractDependencyInjection;
use TypedCollection\DependencyInjectionManifest as TCDI;
use Database\Service\DmlNativeQueryRunner;
use Database\Service\NativeQueryDbReader;
use Database\Service\ParameterNamesFromRawQueryExtractor;
use Database\Service\PdoParameterNamesChecker;
use Database\Service\WhereInPdoParametrizer;
use Database\UseCase\CheckPdoParameterNames;
use Database\UseCase\ExtractParameterNamesFromRawQuery;
use Database\UseCase\ParametrizeWhereInPdo;
use Database\UseCase\ReadDbNativeQuery;
use Database\UseCase\RunDmlNativeQuery;

use function DI\autowire;

class DependencyInjectionManifest extends AbstractDependencyInjection
{
    public static function getDependencies(): array
    {
        return array_merge(
            TCDI::getDependencies(),
            [
                RunDmlNativeQuery::class => autowire(DmlNativeQueryRunner::class),
                ParametrizeWhereInPdo::class => autowire(WhereInPdoParametrizer::class),
                CheckPdoParameterNames::class => autowire(PdoParameterNamesChecker::class),
                ExtractParameterNamesFromRawQuery::class => autowire(ParameterNamesFromRawQueryExtractor::class),
                ReadDbNativeQuery::class => autowire(NativeQueryDbReader::class)
            ]
        );
    }
}
