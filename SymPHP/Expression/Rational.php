<?php

namespace SymPHP\Expression;

function gcd(int $a, int $b) : int
{
    $m = $a % $b;
    if ($m === 0) return $b;

    return gcd($b, $m);
}

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

    public function simplify()
    {
        if ($this->num === 0) {
            return new Integer(0);
        }
        else {
            $n = gcd($this->num, $this->denom);
            $num = $this->num / $n; $denom = $this->denom / $n;

            if ($denom === 1) {
                return new Integer($num);
            }

            return new Rational($num, $denom);
        }

        return $this;
    }
}
