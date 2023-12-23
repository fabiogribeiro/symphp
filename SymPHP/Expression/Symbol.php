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
}
