<?php

namespace SymPHP\Expression;

class Add
{
    use Operation;

    public function __construct(...$terms)
    {   
        $this->terms = $terms;
    }

    public function simplify()
    {
        $r = new Integer(0);
        $rest = [];

        foreach ($this->terms as $term) {
            $term = $term->simplify();

            if (!($term instanceof Symbol) && isset($term->isAtom)) {
                $r = $r->add($term);
            }
            else {
                $rest[] = $term;
            }
        }

        if (!$rest) {
            return $r;
        }
        if ($r->num === 0) {
            return new Add(...$rest);
        }

        return new Add(...array_merge([$r], $rest));
    }
}
