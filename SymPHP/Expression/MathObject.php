<?php

namespace SymPHP\Expression;

interface MathObject
{
    public function add(MathObject $other): MathObject;
    public function sub(?MathObject $other): MathObject;
    public function mul(MathObject $other): MathObject;
    public function div(?MathObject $other): MathObject;
    public function equals(MathObject $other, ?float $tolerance): bool;
    public function simplify(): MathObject;
    public function evaluate(?array $symbols): MathObject;
    public function flatten(): MathObject;
    public function __toString(): string;
}
