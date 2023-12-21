<?php

namespace SymPHP\Expression;

class Exp
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = $terms;   
    }
}
