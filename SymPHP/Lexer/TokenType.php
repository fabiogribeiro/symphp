<?php

namespace SymPHP\Lexer;

enum TokenType : string
{
    case Integer = 'Integer';
    case Float = 'Float';
    case Symbol = 'Symbol';
    case Open = '(';
    case Close = ')';
    case Addition = 'Addition';
    case Subtraction = 'Subtraction';
    case Multiplication = 'Multiplication';
    case Division = 'Division';
    case Exponentiation = 'Exponentiation';
    case Factorial = 'Factorial';
    case Function = 'Function';
    case Constant = 'Constant';
    case Terminator = 'Terminator';
    case Space = 'Space';
}
