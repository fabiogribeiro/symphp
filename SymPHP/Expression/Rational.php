<?php

namespace SymPHP\Expression;

class Rational
{
    use Atom;

    public function __construct($num, $denom)
    {
        $this->num = $num;
        $this->denom = $denom;
    }

    public function __toString()
    {
        return $this->num . '/' . $this->denom;
    }

    public function add($other)
    {
        if ($other instanceof Integer || $other instanceof Rational) {
            return new Rational($this->num * $other->denom + $other->num * $this->denom,
                                $this->denom * $other->denom);
        }
        elseif ($other instanceof Real) {
            return new Real($this->num/$this->denom + $other->num);
        }

        return new Add($this, $other);
    }

    public function sub($other=null)
    {
        if (!$other) {
            return new Rational(-$this->num, $this->denom);
        }

        return $this->add($other->sub());
    }

    public function mul($other)
    {
        if ($other instanceof Integer || $other instanceof Rational) {
            return new Rational($this->num * $other->num, $this->denom * $other->denom);
        }
        elseif ($other instanceof Real) {
            return new Real($this->num/$this->denom * $other->num);
        }

        return new Mul($this, $other);
    }

    public function div($other=null)
    {
        if (!$other) {
            return new Rational($this->denom, $this->num);
        }

        return $this->mul($other->div());
    }
}
