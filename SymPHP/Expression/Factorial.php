<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Factorial implements MathObject
{
    use Operation;

    public function __construct($expr)
    {
        $this->terms = [$expr];   
    }

    public function simplify(): MathObject
    {
        return $this;
    }

    public function evaluate(?array $symbols = null): MathObject
    {
        $this->terms[0] = $this->terms[0]->evaluate($symbols)->simplify();
        if (isset($this->terms[0]->isAtom)) {
            return new Integer($this->factorial(intval($this->terms[0]->num)));
        }

        return $this;
    }

    private function factorial(int $n)
    {
        $r = 1;
        for ($i = 2; $i <= $n; ++$i) $r *= $i;

        return $r;
    }
}
