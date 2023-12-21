<?php

namespace SymPHP\Expression;

class Integer
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }
}
