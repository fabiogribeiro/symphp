<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Real implements MathObject
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }

    public function add(MathObject $other): MathObject
    {
        if ($other instanceof Real || $other instanceof Integer) {
            return new Real($this->num + $other->num);
        }
        elseif ($other instanceof Rational || $other instanceof Complex) {
            return $other->add($this);
        }

        return new Add($this, $other);
    }

    public function sub(MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Real(-$this->num);
        }

        return $this->add($other->sub());
    }

    public function mul(MathObject $other): MathObject
    {
        if ($other instanceof Real || $other instanceof Integer) {
            return new Real($this->num * $other->num);
        }
        elseif ($other instanceof Rational || $other instanceof Complex) {
            return $other->mul($this);
        }

        return new Mul($this, $other);
    }

    public function div(MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Real(1/$this->num);
        }

        return $this->mul($other->div());
    }

    public function exp(MathObject $other): MathObject
    {
        if (!($other instanceof Symbol) && isset($other->isAtom)) {
            return new Real(pow($this->num, $other->num/$other->denom));
        }

        return new Exp($this, $other);
    }
}
