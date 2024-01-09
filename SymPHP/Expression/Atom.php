<?php

namespace SymPHP\Expression;

trait Atom
{
    public $num;
    public $denom = 1;
    public bool $isAtom = true;

    public function __toString()
    {
        return $this->num;
    }

    public function evaluate(?array $symbols=null): MathObject
    {
        return $this;
    }

    public function simplify(): MathObject
    {
        return $this;
    }

    public function equals(MathObject $other): bool
    {
        return $this == $other;
    }

    public function flatten(): MathObject
    {
        return $this;
    }
}
