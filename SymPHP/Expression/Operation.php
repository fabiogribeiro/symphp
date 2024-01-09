<?php

namespace SymPHP\Expression;

use SymPHP\Expression\MathObject;

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
    public function flatten(): MathObject
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

                return $el;
            }, $this->terms);

            return (new Add(array_shift($ts), (new Mul(new Integer(-1), ...$ts))->distribute()))->flatten();
        }
        elseif ($this instanceof Div) {
            if (count($this->terms) === 2) {
                return (new Mul($this->terms[0], new Exp($this->terms[1], new Integer(-1))))->flatten();
            }
        }

        return $this;
    }

    public function equals($other): bool
    {
        return $this == $other || $this->sub($other)->flatten()->simplify() == new Integer(0);
    }

    public function add(MathObject $other): MathObject
    {
        return new Add($this, $other);
    }

    public function sub(?MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Mul(new Integer(-1), $this);
        }

        return new Sub($this, $other);
    }

    public function mul(MathObject $other): MathObject
    {
        return new Mul($this, $other);
    }

    public function div(MathObject $other=null): MathObject
    {
        if (!$other) {
            return new Div(new Integer(1), $this);
        }

        return new Div($this, $other);
    }

    public function evaluate(?array $symbols=null): MathObject
    {
        for ($i = 0; $i < count($this->terms); ++$i) {
            $this->terms[$i] = $this->terms[$i]->evaluate($symbols);
        }

        return $this->simplify();
    }
}
