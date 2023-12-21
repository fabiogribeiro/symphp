<?php

namespace SymPHP\Expression;

class Div
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }
}
