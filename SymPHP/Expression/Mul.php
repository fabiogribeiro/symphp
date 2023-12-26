<?php

namespace SymPHP\Expression;

class Mul
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = $terms;   
    }

    public function simplify()
    {
        $r = new Integer(1);
        $rest = [];

        foreach ($this->terms as $term) {
            $term = $term->simplify();

            if (!($term instanceof Symbol) && isset($term->isAtom)) {
                $r = $r->mul($term);
            }
            else {
                $rest[] = $term;
            }
        }

        if (!$rest) {
            return $r;
        }
        if ($r->num === 1 && $r->denom === 1) {
            return new Mul(...$rest);
        }

        return new Mul(...array_merge([$r], $rest));
    }
}
