<?php

namespace SymPHP\Expression;

trait Atom
{
    public $num;
    public $denom = 1;

    public function __toString()
    {
        return $this->num;
    }
}
