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

        return $this->mul($other->div());
    }

    public function simplify()
    {
        return $this;
    }
}
