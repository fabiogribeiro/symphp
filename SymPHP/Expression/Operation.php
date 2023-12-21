<?php

namespace SymPHP\Expression;

trait Operation
{
    public array $terms;

    public function __toString()
    {
        $className = array_slice(explode("\\", get_class($this)), -1, 1)[0];

        return $className . "(" . implode(', ', $this->terms) . ")";
    }
}
