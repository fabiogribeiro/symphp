<?php

namespace SymPHP\Parser;

use Exception;

class Stack
{
    private array $stack = [];

    public function push($el) : void
    {
        $this->stack[] = $el;
    }

    public function pop()
    {
        if ($this->empty()) {
            throw new Exception("Syntax error.");
        }

        return array_pop($this->stack);
    }

    public function top()
    {
        return end($this->stack);
    }

    public function empty() : bool
    {
        return count($this->stack) === 0;
    }

    public function count() : int
    {
        return count($this->stack);
    }

    public function __toString()
    {
        return implode("; ", $this->stack);
    }
}
