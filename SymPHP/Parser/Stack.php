<?php

namespace SymPHP\Parser;

class Stack
{
    private $stack = [];

    public function push($el)
    {
        $this->stack[] = $el;
    }

    public function pop()
    {
        return array_pop($this->stack);
    }

    public function top()
    {
        return end($stack);
    }

    public function empty()
    {
        return count($this->stack) === 0;
    }

    public function count()
    {
        return count($this->stack);
    }

    public function __toString()
    {
        return implode("; ", $this->stack);
    }
}
