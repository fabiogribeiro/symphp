<?php

namespace SymPHP\Expression;

class Mul
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = [];
        foreach($terms as $term) {
            if ($term instanceof Integer && $term->num === 1) {
                continue;
            }

            $this->terms[] = $term;
        }
    }

    public function simplify()
    {
        $r = new Integer(1);
        $rest = [];

        foreach ($this->terms as $term) {
            $term = $term->simplify();

            if (!($term instanceof Symbol) && isset($term->isAtom)) {
                if ($term->num === 0) {
                    return new Integer(0);
                }

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
            if (count($rest) === 1) {
                return $rest[0];
            }

            return new Mul(...$rest);
        }

        return new Mul(...array_merge([$r], $rest));
    }

    public function asCoeff()
    {
        if (isset($this->terms[0]->isAtom) && !($this->terms[0] instanceof Symbol)) {
            $rest = array_splice($this->terms, 1);
            if (count($rest) > 1) {
                return [$this->terms[0], new Mul(...$rest)];
            }

            return [$this->terms[0], $rest[0]];
        }

        return null;
    }
}
