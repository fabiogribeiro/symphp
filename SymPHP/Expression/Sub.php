<?php

namespace SymPHP\Expression;

class Sub
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }
}
