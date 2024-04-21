<?php

declare(strict_types=1);

namespace Database\Tests\Service;

use PHPUnit\Framework\TestCase;
use Database\Service\ParameterNamesFromRawQueryExtractor;
use Database\Service\PdoParameterNamesChecker;

class ParameterNamesFromRawQueryExtractorTest extends TestCase
{
    private ParameterNamesFromRawQueryExtractor $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new ParameterNamesFromRawQueryExtractor(new PdoParameterNamesChecker());
    }

    public function correctlyExtractParametersFromStringDataProvider(): array
    {
        return [
            ["", []],
            [":param1", [":param1"]],
            [":param1,:param2", [":param1", ":param2"]],
            [":param1, param2", [":param1"]],

            ["(column=:param1)", [":param1"]],
            ["(column= :param1)", [":param1"]],
            ["(column= :param1 AND column=:param2)", [":param1", ":param2"]],
            [":param1:param2:param3", [":param1", ":param2", ":param3"]],
            ["SELECT * FROM <my_table> WHERE column1 = :param1 AND column2 <> :param2", [':param1', ':param2']]
        ];
    }

    /**
     * @dataProvider correctlyExtractParametersFromStringDataProvider
     */
    public function testCorrectExtractsParametersFromString(string $query, array $expected): void
    {
        $actual = $this->sut->extract($query);

        self::assertSame($expected, $actual);
    }
}
