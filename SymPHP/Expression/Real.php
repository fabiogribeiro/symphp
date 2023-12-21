<?php

namespace SymPHP\Expression;

class Real
{
    use Atom;

    public function __construct($val)
    {
        $this->num = $val;
    }
}
