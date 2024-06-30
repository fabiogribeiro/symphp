<?php

namespace SymPHP\Expression;

use SymPHP\Expression\Add;
use SymPHP\Expression\Sub;
use SymPHP\Expression\Mul;
use SymPHP\Expression\Div;
use SymPHP\Expression\Atom;
use SymPHP\Expression\Integer;
use SymPHP\Expression\MathObject;

class Infty implements MathObject
{
    use Atom;

    public function add(MathObject $other): MathObject
    {
        if ($other instanceof Infty) {
            return $this;
        }

        return new Add($this, $other);
    }

    public function sub(?MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Mul(new Integer(-1), new Infty());
        }

        return new Add($this, $other->sub());
    }

    public function mul(MathObject $other): MathObject
    {
        if ($other instanceof Infty) {
            return new Infty($this->num * $other->num);
        }

        return new Mul($this, $other);
    }

    public function div(?MathObject $other): MathObject
    {
        if (!$other) {
            return new Div(new Integer(1), new Infty());
        }

        return new Mul($this, $other->div());
    }

    public function equals(MathObject $other, ?float $tolerance = null): bool
    {
        return $other instanceof Infty;
    }

    public function __toString(): string
    {
        return "oo";
    }
}
