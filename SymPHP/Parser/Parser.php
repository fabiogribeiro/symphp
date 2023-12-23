<?php

namespace SymPHP\Parser;

use Exception;
use SymPHP\Lexer\{Lexer, Token, TokenType};
use SymPHP\Parser\Stack;
use SymPHP\Expression\{Integer, Real, Add, Sub, Mul, Div, Exp, Factorial, Symbol, Func};

class Parser
{
    private Lexer $lexer;
    private Stack $operatorStack;
    private Stack $outputStack;

    public function __construct()
    {
        $this->lexer = new Lexer();
        $this->operatorStack = new Stack();
        $this->outputStack = new Stack();
    }

    public function parse(string $input)
    {
        $lastProcessedToken = null;
        $tokens = $this->lexer->tokenize($input);


        foreach ($tokens as $token) {
            if ($token->isAtom) {
                if ($lastProcessedToken?->isAtom) {
                    throw new Exception("Syntax error");
                }
                else {
                    $this->pushToOutput($token);
                }
            }
            elseif ($token->isOperator) {
                if ($lastProcessedToken?->isOperator) {
                    if (($lastProcessedToken?->type === TokenType::Addition ||
                        $lastProcessedToken?->type === TokenType::Subtraction) &&
                        ($token->type === TokenType::Addition ||
                        $token->type === TokenType::Subtraction)) {
                            if ($token->type === TokenType::Subtraction) {
                                $this->pushToOutput(new Token('-1', TokenType::Integer));
                                $this->processOperator(new Token('*', TokenType::Multiplication));
                            }
                        }
                    elseif ($lastProcessedToken?->type === TokenType::Factorial) {
                        $this->processOperator($token);
                    }
                    else {
                        throw new Exception("Syntax error.");
                    }
                }
                elseif (!$lastProcessedToken || $lastProcessedToken->type === TokenType::Open ||
                                                $lastProcessedToken->type == TokenType::Function) {

                    if ($token->type === TokenType::Multiplication || $token->type === TokenType::Division) {
                        throw new Exception("Syntax error.");
                    }

                    if ($token->type === TokenType::Subtraction) {
                        $this->pushToOutput(new Token('-1', TokenType::Integer));
                        $this->processOperator(new Token('*', TokenType::Multiplication));
                    }
                }
                else {
                    $this->processOperator($token);
                }
            }
            elseif ($token->type === TokenType::Open) {
                if ($lastProcessedToken?->isAtom) {
                    throw new Exception("Syntax error.");
                }

                $this->operatorStack->push($token);
            }
            elseif ($token->type === TokenType::Close) {
                if ($this->operatorStack->empty() ||
                    ($lastProcessedToken?->isOperator && $lastProcessedToken?->type !== TokenType::Factorial)) {
                    throw new Exception("Syntax error.");
                }

                $op = $this->operatorStack->pop();
                while ($op->type !== TokenType::Open) {
                    $this->pushToOutput($op);

                    $op = $this->operatorStack->pop();
                    if (!$op) {
                        throw new Exception("Syntax error.");
                    }
                }

                if (($op = $this->operatorStack->top()) && $op->type === TokenType::Function) {
                    $this->pushToOutput($this->operatorStack->pop());
                }
            }
            elseif ($token->type === TokenType::Function) {
                if ($lastProcessedToken && !($lastProcessedToken->isOperator ||
                                            $lastProcessedToken->type === TokenType::Open)) {

                    throw new Exception("Syntax error.");
                }

                $this->operatorStack->push($token);
            }

            $lastProcessedToken = $token;
        }

        if ($lastProcessedToken?->isOperator && $lastProcessedToken?->type !== TokenType::Factorial) {
            throw new Exception("Syntax error.");
        }

        while (!$this->operatorStack->empty()) {
            $op = $this->operatorStack->pop();
            if ($op->type === TokenType::Open) {
                throw new Exception("Syntax error.");
            }

            $this->pushToOutput($op);
        }

        if ($this->outputStack->count() !== 1) {
            // Only one tree at the end.
            throw new Exception("Syntax error.");
        }

        return $this->outputStack->pop();
    }

    private function getPrecedence(Token $token) : int
    {
        switch($token->type) {
            case TokenType::Addition:
            case TokenType::Subtraction:
                return 1;
            case TokenType::Multiplication:
            case TokenType::Division:
            case TokenType::Function:
                return 2;
            case TokenType::Exponentiation:
                return 3;
            case TokenType::Factorial:
                return 4;
        }

        return 0;
    }

    private function leastPrecedence(Token $opA, Token $opB) : bool
    {
        $aprec = $this->getPrecedence($opA);
        $bprec = $this->getPrecedence($opB);

        if ($aprec < $bprec) return true;
        elseif ($aprec > $bprec) return false;

        return $opA->isLeftAssoc;
    }

    private function processOperator(Token $op)
    {
        while (!$this->operatorStack->empty() &&
                $this->leastPrecedence($op, $this->operatorStack->top())) {

            $this->pushToOutput($this->operatorStack->pop());
        }

        $this->operatorStack->push($op);
    }

    private function pushToOutput(Token $token)
    {
        $out = null;

        // Process token
        switch($token->type) {
            case TokenType::Integer:
                $out = new Integer($token->value);
                break;
            case TokenType::Float:
                $out = new Real($token->value);
                break;
            case TokenType::Symbol:
                $out = new Symbol($token->value);
                break;
            case TokenType::Addition:
                $a = $this->outputStack->pop();
                $b = $this->outputStack->pop();
                $out = new Add($b, $a);
                break;
            case TokenType::Subtraction:
                $a = $this->outputStack->pop();
                $b = $this->outputStack->pop();
                $out = new Sub($b, $a);
                break;
            case TokenType::Multiplication:
                $a = $this->outputStack->pop();
                $b = $this->outputStack->pop();
                $out = new Mul($b, $a);
                break;
            case TokenType::Division:
                $a = $this->outputStack->pop();
                $b = $this->outputStack->pop();
                $out = new Div($b, $a);
                break;
            case TokenType::Exponentiation:
                $a = $this->outputStack->pop();
                $b = $this->outputStack->pop();
                $out = new Exp($b, $a);
                break;
            case TokenType::Factorial:
                $out = new Factorial($this->outputStack->pop());
                break;
            case TokenType::Function:
                $out = new Func($this->outputStack->pop());
                break;
        }

        $this->outputStack->push($out);
    }
}
