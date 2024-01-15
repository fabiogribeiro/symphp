<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use SymPHP\Parser\Parser;

class ExpressionTest extends TestCase
{
    private Parser $parser;

    public function setUp(): void
    {
        $this->parser = new Parser();
    }

    public static function atomOpsDataProvider(): array
    {
        return [
            ['1 + 1', '2'],
            ['1 - 1', '0'],
            ['-4 / 2', '-2'],
            ['-2 * 2', '-4'],
            ['2^3', '8'],
            ['1 + x', 'Add(1, x)'],
            ['1 - x', 'Add(1, Mul(-1, x))'],
            ['3 * x', 'Mul(3, x)'],
            ['3y', 'Mul(3, y)'],
            ['1 / x', 'Div(1, x)'],
            ['x^3', 'Exp(x, 3)'],
        ];
    }

    #[TestDox('Simplifies atom operation \'$input\' if possible')]
    #[DataProvider('atomOpsDataProvider')]
    public function testSimplifyAtomOperations(string $input, string $expected): void
    {
        $expr = $this->parser->parse($input)->simplify();
        $this->assertEquals($expr->__toString(), $expected);
    }

    public function testCanFlatten(): void
    {
        $addStr = 'Add(1, 2, 3)';
        $mulStr = 'Mul(1, 2, 3)';

        $expr = $this->parser->parse('1 + 2 + 3');
        $this->assertNotEquals($expr->__toString(), $addStr);
        $this->assertEquals($expr->flatten()->__toString(), $addStr);

        $expr = $this->parser->parse('1 * 2 * 3');
        $this->assertNotEquals($expr->__toString(), $mulStr);
        $this->assertEquals($expr->flatten()->__toString(), $mulStr);
    }

    public function testCanDistribute(): void
    {
        // TODO: Line looks weird, do more auto simplification?
        $expr = $this->parser->parse('-1 * (3 - x)')->simplify()->distribute()->flatten()->simplify();
        $this->assertEquals($expr->__toString(), 'Add(-3, x)');
    }

    public function testCanEvaluate(): void
    {
        $expr = $this->parser->parse('3! + sin(x)');
        $this->assertNotEquals($expr->__toString(), '6');
        $this->assertEquals($expr->evaluate(['x' => 0])->__toString(), '6');

        $expr = $this->parser->parse('3/2')->simplify()->evaluate();
        $this->assertEqualsWithDelta(floatval($expr->__toString()), 1.5, 1e-7);
    }

    public function testCanSimplifyPolynomial(): void
    {
        $expr = $this->parser->parse('1 + 1 + x + -5 + x^2 - 2*x^2')->flatten()->simplify();
        $this->assertEquals($expr->__toString(), 'Add(-3, x, Mul(-1, Exp(x, 2)))');
    }

    public function testCanCompareBasicExpressions(): void
    {
        $a = $this->parser->parse('2*x^2 - 3 + x/b')->flatten()->simplify();
        $b = $this->parser->parse('-3 + x/b + 2*x^2')->flatten()->simplify();
        $this->assertTrue($a->equals($b));

        $c = $this->parser->parse('1.5');
        $d = $this->parser->parse('3/2')->simplify();
        $this->assertTrue($c->equals($d, 1e-7));
    }
}
