<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use SymPHP\Parser\Parser;

class ParserTest extends TestCase
{
    private Parser $parser;

    public static function nonsenseDataProvider(): array
    {
        return [[' '], ['@'], ['-'], ['2x'], ['1 +'], ['1 + 1 /'], ['/ 1 + 1'], ['1 -* 3']];
    }

    public static function atomsDataProvider(): array
    {
        return [['2', '2'], ['1,1', '1.1'], ['1.1', '1.1'], ['x', 'x'],
                ['sin(2)', 'sin(2)'], ['x!', 'Factorial(x)']];
    }

    public function setUp() : void
    {
        $this->parser = new Parser();
    }

    #[TestDox('Detects nonsense parsing string \'$input\'')]
    #[DataProvider('nonsenseDataProvider')]
    public function testDetectsNonsense(string $input): void
    {
        $this->expectException('Exception');
        $this->parser->parse($input);
    }


    #[TestDox('Parses atom \'$input\' correctly')]
    #[DataProvider('atomsDataProvider')]
    public function testParsesAtoms(string $input, string $expected): void
    {
        $this->assertEquals($this->parser->parse($input)->__toString(), $expected);
    }
}
