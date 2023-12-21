<?php

namespace SymPHP\Expression;

class Symbol
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }
}
