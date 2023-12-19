<?php

namespace SymPHP\Lexer;

use SymPHP\Lexer\Token;
use SymPHP\Lexer\TokenType;

class Lexer
{
    private const PATTERNS = [
        '/-?\d+[,\.]\d+(e[+-]?\d+)?/' => TokenType::Float,
        '/-?\d+/' => TokenType::Integer,
        '/\(/' => TokenType::Open,
        '/\)/' => TokenType::Close,
        '/\+/' => TokenType::Addition,
        '/\-/' => TokenType::Subtraction,
        '/\*/' => TokenType::Multiplication,
        '/\//' => TokenType::Division,
        '/\^/' => TokenType::Exponentiation,
        '/\!/' => TokenType::Factorial,
        '/[a-zA-Z]/' => TokenType::Symbol,
        '/\n/' => TokenType::Terminator,
        '/\s+/' => TokenType::Space,
    ];

    public function tokenize(string $input)
    {
        $tokens = [];
        $index = 0;
        $strsize = strlen($input);

        while ($index < $strsize) {
            $token = null;

            foreach ($this::PATTERNS as $pattern => $tokenType) {
                if (preg_match($pattern, substr($input, $index), $matches, PREG_OFFSET_CAPTURE)) {
                    // Match only in the beginning of string.
                    if ($matches[0][1] !== 0) continue;

                    $token = new Token($matches[0][0], $tokenType);
                    break;
                }
            }

            if (!$token) {
                echo "Error in lexing.\n";
                return [];
            }

            $tokens[] = $token;
            $index += strlen($token->value);
        }

        return array_filter($tokens, function(Token $tkn) {
            return $tkn->type !== TokenType::Space;
        });
    }
}
