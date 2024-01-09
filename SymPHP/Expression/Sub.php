<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Sub implements MathObject
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }

    public function simplify(): MathObject
    {
        return $this->flatten()->simplify();
    }
}
