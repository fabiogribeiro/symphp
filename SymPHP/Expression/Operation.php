<?php

namespace SymPHP\Expression;

trait Operation
{
    public array $terms;
    public bool $isOperation = true;

    public function __toString()
    {
        $className = array_slice(explode("\\", $this::class), -1, 1)[0];

        return $className . "(" . implode(', ', $this->terms) . ")";
    }

    /**
     *  Since +, * is associative, try to group as many as possible in the same op.
     */
    public function flatten()
    {
        if ($this instanceof Add || $this instanceof Mul) {
            $className = $this::class;
            $res = new $className();

            foreach ($this->terms as $term) {
                if (isset($term->isOperation)) {
                    $term = $term->flatten();
                }

                if ($term instanceof $className) {
                    $res->terms = array_merge($res->terms, $term->terms);
                }
                else {
                    $res->terms[] = $term;
                }
            }

            return $res;
        }
        elseif ($this instanceof Sub) {
            $ts = array_map(function ($el) {
                if (isset($el->isOperation)) {
                    return $el->flatten();
                }
                else {
                    return $el;
                }
            }, $this->terms);

            return (new Add(array_shift($ts), new Mul(new Integer(-1), ...$ts)))->flatten();
        }
        return $this;
    }
}
