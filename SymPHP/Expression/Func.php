<?php

namespace SymPHP\Expression;

class Func
{
    use Operation;

    public function __construct($name, $expr)
    {
        $this->terms = [$name, $expr];
    }

    public function simplify()
    {
        return $this;
    }

    public function evaluate(array $symbols=null)
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
