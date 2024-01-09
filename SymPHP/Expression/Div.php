<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Div implements MathObject
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }

    public function simplify(): MathObject
    {
        if (($n = count($this->terms)) > 2) {
            $this->terms[1] = (new Mul(...array_slice($this->terms, 1)))->simplify();
        }

        $a = $this->terms[0]->simplify();
        $b = $this->terms[1]->simplify();
        if (isset($a->isAtom) && isset($b->isAtom)) {
            return $a->div($b);
        }

        return new Div($a, $b);
    }
}
