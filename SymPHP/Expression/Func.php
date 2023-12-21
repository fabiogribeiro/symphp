<?php

namespace SymPHP\Expression;

class Func
{
    use Operation;

    public function __construct($expr)
    {
        $this->terms = [$expr];   
    }
}
