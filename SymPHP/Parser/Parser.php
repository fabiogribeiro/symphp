<?php

namespace SymPHP\Parser;

use Exception;
use SymPHP\Lexer\{Lexer, Token, TokenType};
use SymPHP\Parser\Stack;

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
                    $this->outputStack->push($token);
                }
            }
            elseif ($token->isOperator) {
                if ($lastProcessedToken?->isOperator) {
                    if (($lastProcessedToken?->type === TokenType::Addition ||
                        $lastProcessedToken?->type === TokenType::Subtraction) &&
                        ($token->type === TokenType::Addition ||
                        $token->type === TokenType::Subtraction)) {
                            if ($token->type === TokenType::Subtraction) {
                                $this->outputStack->push(new Token('-1', TokenType::Integer));
                                $this->processOperator(new Token('*', TokenType::Multiplication));       
                            }
                        }
                    else {
                        throw new Exception("Syntax error.");
                    }
                }
                elseif (!$lastProcessedToken || $lastProcessedToken->type === TokenType::Open) {
                    if ($token->type === TokenType::Multiplication || $token->type === TokenType::Division) {
                        throw new Exception("Syntax error.");
                    }

                    if ($token->type === TokenType::Subtraction) {
                        $this->outputStack->push(new Token('-1', TokenType::Integer));
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
                if ($this->operatorStack->empty() || $lastProcessedToken?->isOperator) {
                    throw new Exception("Syntax error.");
                }

                $op = $this->operatorStack->pop();
                while ($op->type !== TokenType::Open) {
                    $this->outputStack->push($op);

                    $op = $this->operatorStack->pop();
                    if (!$op) {
                        throw new Exception("Syntax error.");
                    }
                }
            }

            $lastProcessedToken = $token;
        }

        if ($lastProcessedToken?->isOperator) {
            throw new Exception("Syntax error.");
        }

        while ($op = $this->operatorStack->pop()) {
            if ($op->type === TokenType::Open) {
                throw new Exception("Syntax error.");
            }

            $this->outputStack->push($op);
        }

        return $this->outputStack;
    }

    private function getPrecedence(Token $token) : int
    {
        switch($token->type) {
            case TokenType::Addition:
            case TokenType::Subtraction:
                return 1;
            case TokenType::Multiplication:
            case TokenType::Division:
                return 2;
            case TokenType::Exponentiation:
                return 3;
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

            $this->outputStack->push($this->operatorStack->pop());
        }

        $this->operatorStack->push($op);
    }
}
