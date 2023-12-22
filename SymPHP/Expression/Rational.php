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
}
