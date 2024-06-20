<?php

declare(strict_types=1);

namespace Database\Tests\Service;

use PHPUnit\Framework\TestCase;
use Database\Service\CustomQueryParameterNamesChecker;

class PdoParameterNameCheckerTest extends TestCase
{
    private CustomQueryParameterNamesChecker $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new CustomQueryParameterNamesChecker();
    }

    public function correctlyIdentifiesPdoParameterNamesDataProvider(): array
    {
        return [
            ["param1 param2", false],
            [" param1 ", false],
            [" :param1 ", false],

            ["-:param:+", true],
            ["-:param:+", true],
            ["-:param123:+", true],
            ["-:parAm123__:+", true]
        ];
    }

    /**
     * @dataProvider correctlyIdentifiesPdoParameterNamesDataProvider
     */
    public function testCorrectlyIdentifiesPdoParameterNames(string $pdoParameterName, bool $expected): void
    {
        $actual = $this->sut->checkStringRepresentsParameterName($pdoParameterName);

        self::assertEquals($expected, $actual);
    }
}
