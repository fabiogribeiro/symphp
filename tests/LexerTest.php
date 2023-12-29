<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use SymPHP\Lexer\Lexer;
use SymPHP\Lexer\Token;
use SymPHP\Lexer\TokenType;


class LexerTest extends TestCase
{
    private $lexer;

    public static function tokenDataProvider(): array
    {
        return [
            ['1', '1', TokenType::Integer],
            ['1,1', '1,1', TokenType::Float],
            ['1.1', '1.1', TokenType::Float],
            ['y', 'y', TokenType::Symbol],
            ['(', '(', TokenType::Open],
            [')', ')', TokenType::Close],
            ['!', '!', TokenType::Factorial],
            ['+', '+', TokenType::Addition],
            ['-', '-', TokenType::Subtraction],
            ['*', '*', TokenType::Multiplication],
            ['/', '/', TokenType::Division],
            ['^', '^', TokenType::Exponentiation],
            ['sin', 'sin', TokenType::Function],
        ];
    }

    public function setUp() : void
    {
        $this->lexer = new Lexer();
    }

    public function testEdgeCases() : void
    {
        $this->assertEquals(0, count($this->lexer->tokenize('  ')));
        $this->assertEquals(3, count($this->lexer->tokenize('3 + 1.1')));

        $this->expectException('Exception');
        $this->lexer->tokenize('@');
    }

    #[TestDox('Tokenizes individual token $input')]
    #[DataProvider('tokenDataProvider')]
    public function testIndividualToken(string $input, string $val, TokenType $type) : void
    {
        $tkn = $this->lexer->tokenize($input)[0];
        $this->assertEquals($val, $tkn->value);
        $this->assertEquals($type, $tkn->type);
    }

    public function testTokenizesFancyExpression() : void
    {
        $tkns = array_values($this->lexer->tokenize('3 + 5 * 2 - 1 / 5 ^ 2!'));
        $this->assertEquals('5', $tkns[2]->value);
        $this->assertEquals('-', $tkns[5]->value);
        $this->assertEquals('2', $tkns[10]->value);
        $this->assertEquals('!', $tkns[11]->value);
    }
}
