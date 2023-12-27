<?php

namespace SymPHP\Expression;

class Integer
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }

    public function add($other)
    {
        if ($other instanceof Integer) {
            return new Integer($this->num  + $other->num);
        }
        elseif ($other instanceof Real) {
            return new Real($this->num  + $other->num);
        }
        elseif ($other instanceof Rational) {
            return $other->add($this);
        }

        return new Add($this, $other);
    }

    public function sub($other=null)
    {
        if (!$other) {
            return new Integer(-$this->num);
        }

        return $this->add($other->sub());
    }

    public function mul($other)
    {
        if ($other instanceof Integer) {
            return new Integer($this->num * $other->num);
        }
        elseif ($other instanceof Real) {
            return new Real($this->num * $other->num);
        }
        elseif ($other instanceof Rational) {
            return $other->mul($this);
        }

        return new Mul($this, $other);
    }

    public function div($other=null)
    {
        if (!$other) {
            return new Rational(1, $this->num);
        }
        if ($this->num === 1) {
            if ($other instanceof Symbol) {
                return new Div($this, $other);
            }

            return new Rational($this, $other);
        }

        return $this->mul($other->div());
    }

    public function exp($other)
    {
        if ($other instanceof Integer) {
            return new Integer(pow($this->num, $other->num));
        }
        elseif(!($other instanceof Symbol) && isset($other->isAtom)) {
            return new Real(pow($this->num, $other->num/$other->denom));
        }

        return new Exp($this, $other);
    }

    public function simplify()
    {
        return $this;
    }
}
