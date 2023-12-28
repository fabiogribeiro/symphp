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
        if (isset($a->isAtom) && isset($b->isAtom)) {
            return $a->exp($b);
        }

        return new Exp($a, $b);
    }

    public function asPow()
    {
        if (isset($this->terms[0]->isAtom) && isset($this->terms[1]->isAtom) && !($this->terms[1] instanceof Symbol)) {
            return [$this->terms[0], $this->terms[1]];
        }

        return null;
    }

    public function asCoeff()
    {
        return [new Integer(1), $this];
    }
}
