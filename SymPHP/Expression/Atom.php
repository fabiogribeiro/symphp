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

    public function equals(MathObject $other, ?float $tolerance=null): bool
    {
        if ($tolerance) {
            $res = $this->sub($other);
            if (!($res instanceof Symbol) && isset($res->isAtom))
                return $res->num / $res->denom < $tolerance;
        }

        return $this == $other;
    }

    public function flatten(): MathObject
    {
        return $this;
    }
}
