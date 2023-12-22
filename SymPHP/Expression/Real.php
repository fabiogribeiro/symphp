<?php

namespace SymPHP\Expression;

class Real
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }

    public function add($other)
    {
        if ($other instanceof Real || $other instanceof Integer) {
            return new Real($this->num + $other->num);
        }

        return new Add($this, $other);
    }

    public function sub($other=null)
    {
        if (!$other) {
            return new Real(-$this->num);
        }

        return $this->add($other->sub());
    }

    public function mul($other)
    {
        if ($other instanceof Real || $other instanceof Integer) {
            return new Real($this->num * $other->num);
        }

        return new Mul($this, $other);
    }

    public function div($other=null)
    {
        if (!$other) {
            return new Real(1/$this->num);
        }

        return $this->mul($other->div());
    }
}
