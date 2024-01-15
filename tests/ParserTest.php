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
        return [[' '], ['@'], ['-'], ['1 +'], ['1 + 1 /'], ['/ 1 + 1'], ['1 -* 3']];
    }

    public static function atomsDataProvider(): array
    {
        return [['2', '2'], ['1,1', '1.1'], ['1.1', '1.1'], ['x', 'x'], ['x_1', 'x_1'],
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

    public function testParsesUnary(): void
    {
        $expr = $this->parser->parse('-3')->simplify();
        echo $expr, " ";

        $expr = $this->parser->parse('--3')->simplify();
        echo $expr, " ";

        $expr = $this->parser->parse('++3')->simplify();
        echo $expr, " ";

        $expr = $this->parser->parse('3 --+3')->simplify();
        echo $expr;

        $this->expectOutputString('-3 3 3 6');
    }

    public function testParsesFancyExpression(): void
    {
        $expr = $this->parser->parse('sin(x^2) + 2 - 4 * 3! + 3 / 2^y');
        $this->expectOutputString('Add(Sub(Add(sin(Exp(x, 2)), 2), Mul(4, Factorial(3))), Div(3, Exp(2, y)))');
        echo $expr;
    }
}
