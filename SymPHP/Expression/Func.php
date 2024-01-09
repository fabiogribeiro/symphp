<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Func implements MathObject
{
    use Operation;

    public function __construct($name, $expr)
    {
        $this->terms = [$name, $expr];
    }

    public function simplify(): MathObject
    {
        return $this;
    }

    public function evaluate(?array $symbols=null): MathObject
    {
        $func = $this->terms[0];
        $expr = $this->terms[1]->evaluate($symbols);

        if (isset($expr->isAtom) && !($expr instanceof Symbol)) {
            return new Real($func($expr->num / $expr->denom));
        }

        return $this;
    }

    public function __toString()
    {
        return $this->terms[0] . "(" . $this->terms[1] . ")";
    }
}
