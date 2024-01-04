## SymPHP

SymPHP is a mathematical library that parses arithmetic expressions and can do some symbolic simplifications.

### Example usage

```php
<?php

use SymPHP\Parser\Parser;

$parser = new Parser();

$expr = $parser->parse('3 - 2^2 + x');
echo $expr, "\n";
// Add(Sub(3, Exp(2, 2)), x) - Representation of the expression

$expr = $expr->simplify();
echo $expr, "\n";
// Add(-1, x) - -1 + x

$expr2 = $parser->parse('-1 + x')->simplify();
echo $expr1->equals($expr2);
// 1 - true - Compares the two expressions

```
### Available functions

```php
<?php

// Basic operations
$expr->add($other);
$expr->sub($other);
$expr->mul($other);
$expr->div($other);
$expr->exp($other);

$expr->flatten();                       // Add(Add(2, 3), 4) -> Add(2, 3, 4)
$expr->evaluate(array $symbols=null);   // Evaluate $expr numerically with symbols replaced
$expr->simplify();                      // Simplify expression
$expr->equals($other);                  // Compare two expressions
$expr->distribute()                     // If a * (b + c) -> a * b + a * c

```

### Running tests

```
$ composer install
$ ./vendor/bin/phpunit tests
```
