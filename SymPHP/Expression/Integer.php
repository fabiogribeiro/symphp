<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Integer implements MathObject
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }

    public function add(MathObject $other): MathObject
    {
        if ($this->num === 0) {
            return $other;
        }
        elseif ($other instanceof Integer) {
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

    public function sub(?MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Integer(-$this->num);
        }

        return $this->add($other->sub());
    }

    public function mul(MathObject $other): MathObject
    {
        if ($this->num === 1 && $this->denom === 1) {
            return $other;
        }
        elseif ($other instanceof Integer) {
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

    public function div(?MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Rational(1, $this->num);
        }

        return $this->mul($other->div());
    }

    public function exp(MathObject $other): MathObject
    {
        if ($other instanceof Integer) {
            return new Integer(pow($this->num, $other->num));
        }
        elseif(!($other instanceof Symbol) && isset($other->isAtom)) {
            return new Real(pow($this->num, $other->num/$other->denom));
        }

        return new Exp($this, $other);
    }
}
