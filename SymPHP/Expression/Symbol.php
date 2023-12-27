<?php

namespace SymPHP\Expression;

class Symbol
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }

    public function add($other)
    {
        return new Add($this, $other);
    }

    public function sub($other=null)
    {
        if (!$other) {
            return new Mul(new Integer(-1), $this);
        }

        return new Sub($this, $other);
    }

    public function mul($other)
    {
        return new Mul($this, $other);
    }

    public function div($other=null)
    {
        if (!$other) {
            return new Div(new Integer(1), $this);
        }

        return new Div($this, $other);
    }

    public function exp($other)
    {
        if (isset($other->isAtom)) {
            if ($other->num === 0) {
                return new Integer(1);
            }
            elseif ($other->num === 1) {
                return $this;
            }
        }

        return new Exp($this, $other);
    }

    public function simplify()
    {
        return $this;
    }

    public function asCoeff()
    {
        return [new Integer(1), $this];
    }
}
