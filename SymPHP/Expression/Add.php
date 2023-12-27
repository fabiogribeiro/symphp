<?php

namespace SymPHP\Expression;

class Add
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = [];
        foreach($terms as $term) {
            if (isset($term->isAtom) && $term->num === 0) {
                continue;
            }

            $this->terms[] = $term;
        }
    }

    public function simplify()
    {
        $r = new Integer(0);
        $similar = [];
        $rest = [];

        foreach ($this->terms as $term) {
            $term = $term->simplify();

            if (!($term instanceof Symbol) && isset($term->isAtom)) {
                $r = $r->add($term);
            }
            elseif (($term instanceof Symbol || $term instanceof Mul) && ($coeff = $term->asCoeff())) {
                $k = $coeff[1]->__toString();
                if (isset($similar[$k])) {
                    $similar[$k][0] = $similar[$k][0]->add($coeff[0]);
                }
                else {
                    $similar[$k] = [$coeff[0], $coeff[1]];
                }
            }
            else {
                $rest[] = $term;
            }
        }

        foreach($similar as $k => $v) {
            $similar[$k] = (new Mul($v[0], $v[1]))->simplify();
        }

        $rest = array_merge(array_values($similar), $rest);
        if (!$rest) {
            return $r;
        }
        if ($r->num === 0) {
            if (count($rest) === 1) {
                return $rest[0];
            }

            return new Add(...$rest);
        }

        return new Add(...array_merge([$r], $rest));
    }
}
