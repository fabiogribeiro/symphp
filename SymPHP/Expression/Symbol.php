<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Symbol implements MathObject
{
    use Atom;

    public readonly ?string $constName;

    public function __construct($val, ?string $name=null)
    {
        $this->constName = $name;

        if ($name === 'pi') {
            $this->num = M_PI;
        }
        else {
            $this->num = $val;
        }
    }

    public function __toString()
    {
        if ($this->constName) {
            return $this->constName;
        }

        return $this->num;
    }

    public function add(MathObject $other): MathObject
    {
        return new Add($this, $other);
    }

    public function sub(MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Mul(new Integer(-1), $this);
        }

        return new Sub($this, $other);
    }

    public function mul(MathObject $other): MathObject
    {
        return new Mul($this, $other);
    }

    public function div(MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Div(new Integer(1), $this);
        }

        return new Div($this, $other);
    }

    public function exp(MathObject $other): MathObject
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

    public function simplify(): MathObject
    {
        return $this;
    }

    public function evaluate(array $symbols=null): MathObject
    {
        // Know constant
        if ($this->constName) {
            return new Real($this->num);
        }

        return new Real(floatval(str_replace(',', '.', $symbols[$this->num])));
    }

    public function asCoeff()
    {
        return [new Integer(1), $this];
    }

    public function asPow()
    {
        return [$this, new Integer(1)];
    }
}
