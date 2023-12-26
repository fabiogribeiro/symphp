<?php

namespace SymPHP\Expression;

class Exp
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = $terms;   
    }

    public function simplify()
    {
        if (($n = count($this->terms)) > 2) {
            $this->terms[1] = (new Mul(...array_slice($this->terms, 1)))->simplify();
        }

        $a = $this->terms[0]->simplify();
        $b = $this->terms[1]->simplify();

        return new Exp($a, $b);
    }
}
