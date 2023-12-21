<?php

namespace SymPHP\Expression;

class Mul
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = $terms;   
    }
}
