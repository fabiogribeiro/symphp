<?php

namespace SymPHP\Expression;

interface MathObject
{
    public function add(MathObject $other): MathObject;
    public function sub(?MathObject $other=null): MathObject;
    public function mul(MathObject $other): MathObject;
    public function div(?MathObject $other=null): MathObject;
    public function equals(MathObject $other): bool;
    public function simplify(): MathObject;
    public function evaluate(?array $symbols): MathObject;
    public function flatten(): MathObject;
}
