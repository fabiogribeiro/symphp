<?php

namespace SymPHP\Lexer;

use SymPHP\Lexer\TokenType;

class Token
{
    public string $value;
    public TokenType $type;
    public readonly bool $isAtom;
    public readonly bool $isOperator;
    public readonly bool $isLeftAssoc;
    public readonly bool $isRightAssoc;

    public function __construct(string $val, TokenType $type)
    {
        $this->value = $val;
        $this->type = $type;

        if ($this->type === TokenType::Integer  ||
            $this->type === TokenType::Float    ||
            $this->type === TokenType::Symbol   ||
            $this->type === TokenType::Constant) {
                $this->isAtom = true;
        }
        else {
            $this->isAtom = false;
        }

        if ($this->type === TokenType::Addition         ||
            $this->type === TokenType::Subtraction      ||
            $this->type === TokenType::Multiplication   ||
            $this->type === TokenType::Division         ||
            $this->type === TokenType::Exponentiation   ||
            $this->type === TokenType::Factorial) {
                $this->isOperator = true;
        }
        else {
            $this->isOperator = false;
        }

        if ($this->isOperator) {
            if ($this->type === TokenType::Exponentiation || $this->type === TokenType::Factorial) {
                $this->isRightAssoc = true;
                $this->isLeftAssoc = false;
            }
            else {
                $this->isLeftAssoc = true;
                $this->isRightAssoc = false;
            }
        }
        else {
            $this->isLeftAssoc = false;
            $this->isRightAssoc = false;
        }
    }

    public function __toString()
    {
        return "Token[" . $this->type->value . "]: " . $this->value;
    }
}
