<?php

namespace SymPHP\Lexer;

use SymPHP\Lexer\TokenType;

class Token
{
    public string $value;
    public TokenType $type;

    public function __construct(string $val, TokenType $type)
    {
        $this->value = $val;
        $this->type = $type;   
    }

    public function __toString()
    {
        return "Token[" . $this->type->value . "]: " . $this->value;
    }
}
