<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Complex implements MathObject
{
    public MathObject $real;
    public MathObject $img;

    public function __construct(MathObject $real, MathObject $img)
    {
        $this->real = $real;
        $this->img = $img;
    }

    public function __toString()
    {
        return $this->real->__toString() . ' + (' . $this->img->__toString() . ')i';
    }

    public function add(MathObject $other): MathObject
    {
        if ($other instanceof Complex) {
            return (new Complex($this->real->add($other->real), $this->img->add($other->img)))->simplify();
        }
        elseif(isset($other->isAtom)) {
            return (new Complex($this->real->add($other), $this->img))->simplify();
        }

        return new Add($this, $other);
    }

    public function sub(?MathObject $other=null): MathObject
    {
        if (!$other) {
            return (new Complex(new Mul(new Integer(-1), $this->real), new Mul(new Integer(-1), $this->img)))->simplify();
        }

        return $this->add($other->sub());
    }

    public function mul(MathObject $other): MathObject
    {
        if ($other instanceof Complex) {
            $r = $this->real->mul($other->real)->sub($this->img->mul($other->img));
            $i = $this->real->mul($other->img)->add($this->img->mul($other->real));

            return (new Complex($r, $i))->simplify();
        }
        elseif (isset($other->isAtom)) {
            return (new Complex($this->real->mul($other), $this->img->mul($other)))->simplify();
        }

        return new Mul($this, $other);
    }

    public function div(?MathObject $other=null): MathObject
    {
        if (!$other) {
            $coeff = (new Div(new Integer(1), $this->real->mul($this->real)->add($this->img->mul($this->img))))->simplify();

            return (new Mul($coeff, new Complex($this->real, $this->img->sub())))->simplify();
        }

        return new Mul($this, $other->div());
    }

    public function exp(MathObject $other): MathObject
    {
        return new Exp($this, $other);
    }

    public function simplify(): MathObject
    {
        $r = $this->real->simplify();
        $c = $this->img->simplify();

        if ($c->equals(new Integer(0)))
            return $r;

        return new Complex($r, $c);
    }

    public function equals(MathObject $other, ?float $tolerance=null): bool
    {
        return ($other instanceof Complex &&
                $this->real->equals($other->real, $tolerance) &&
                $this->img->equals($other->img, $tolerance));
    }

    public function flatten(): MathObject
    {
        return $this;
    }

    public function evaluate(?array $symbols): MathObject
    {
        return (new Complex($this->real->evaluate($symbols), $this->img->evaluate($symbols)))->simplify();
    }
}
