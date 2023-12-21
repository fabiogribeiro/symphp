<?php

namespace SymPHP\Expression;

class Add
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }
}
