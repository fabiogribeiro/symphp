<?php

namespace SymPHP\Expression;

class Factorial
{
    use Operation;

    public function __construct($expr)
    {
        $this->terms = [$expr];   
    }
}
