<?php

namespace SymPHP\Lexer;

enum TokenType : string
{
    case Integer = 'Integer';
    case Float = 'Float';
    case Symbol = 'Symbol';
    case Open = '(';
    case Close = ')';
    case Addition = '+';
    case Subtraction = '-';
    case Multiplication = '*';
    case Division = '/';
    case Exponentiation = '^';
    case Factorial = '!';
    case Function = 'Function';
    case Constant = 'Constant';
    case Terminator = 'Terminator';
    case Space = 'Space';
}
