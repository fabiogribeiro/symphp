<?php

namespace SymPHP\Lexer;

use Exception;
use SymPHP\Lexer\Token;
use SymPHP\Lexer\TokenType;

class Lexer
{
    private const PATTERNS = [
        '/\d+[,\.]\d+(e[+-]?\d+)?/' => TokenType::Float,
        '/\d+/' => TokenType::Integer,
        '/sqrt/' => TokenType::Function,
        '/sin/' => TokenType::Function,
        '/cos/' => TokenType::Function,
        '/tan/' => TokenType::Function,
        '/ln/' => TokenType::Function,
        '/log/' => TokenType::Function,
        '/pi/' => TokenType::Constant,
        '/e/' => TokenType::Constant,
        '/infty/' => TokenType::Infinity,
        '/inf/' => TokenType::Infinity,
        '/oo/' => TokenType::Infinity,
        '/\(/' => TokenType::Open,
        '/\)/' => TokenType::Close,
        '/\+/' => TokenType::Addition,
        '/\-/' => TokenType::Subtraction,
        '/\*/' => TokenType::Multiplication,
        '/\//' => TokenType::Division,
        '/\^/' => TokenType::Exponentiation,
        '/\!/' => TokenType::Factorial,
        '/\w+/' => TokenType::Symbol,
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
                throw new Exception("Syntax error.");
            }

            $tokens[] = $token;
            $index += strlen($token->value);
        }

        return array_filter($tokens, function(Token $tkn) {
            return $tkn->type !== TokenType::Space;
        });
    }
}
