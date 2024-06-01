<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

class Mul implements MathObject
{
    use Operation;

    public function __construct(...$terms)
    {
        $this->terms = $terms;
    }

    public function simplify(): MathObject
    {
        $r = new Integer(1);
        $similar = [];
        $rest = [];

        foreach ($this->terms as $term) {
            $term = $term->simplify();

            if (!($term instanceof Symbol || $term instanceof Infty) && isset($term->isAtom)) {
                if ($term->num === 0) {
                    return new Integer(0);
                }

                $r = $r->mul($term);
            }
            elseif (method_exists($term, 'asPow') && ($coeff = $term->asPow())) {
                $k = $coeff[0]->__toString();
                if (isset($similar[$k]))  {
                    $similar[$k][1] = $similar[$k][1]->add($coeff[1]);
                }
                else {
                    $similar[$k] = [$coeff[0], $coeff[1]];
                }
            }
            else {
                $rest[] = $term;
            }
        }

        foreach ($similar as $k => $v) {
            $similar[$k] = (new Exp($v[0], $v[1]))->simplify();
        }

        $rest = array_merge(array_values($similar), $rest);
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

        return [new Integer(1), $this];
    }

    public function distribute(): MathObject
    {
        if (count($this->terms) == 2 && ($adds = $this->terms[1]) instanceof Add) {
            $newTerms = array_map(function($el) {
                return new Mul($this->terms[0], $el);
            }, $adds->terms);

            return new Add(...$newTerms);
        }

        return $this;
    }
}
