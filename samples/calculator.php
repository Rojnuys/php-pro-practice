<?php

declare(strict_types=1);

use PhpProPractice\App\Calculator\Actions as CalculatorActions;
use PhpProPractice\App\Calculator\Calculator;

$calc = (new Calculator())
    ->registerAction(new CalculatorActions\Add())
    ->registerAction(new CalculatorActions\Sub())
    ->registerAction(new CalculatorActions\Mult())
    ->registerAction(new CalculatorActions\Div())
    ->registerAction(new CalculatorActions\Pow())
    ->registerAction(new CalculatorActions\Root());

echo 'It\'s a calculator!' . PHP_EOL;
echo 'Available actions: ' . implode(',', $calc->getAvailableMarks()) . '.' . PHP_EOL;
echo 'Available format: 12 + 5.' . PHP_EOL;
echo 'Enter operation: ';

$operation = fgets(STDIN);
$operators = explode(' ', $operation);

if (count($operators) < 3 || count($operators) > 3) {
    throw new InvalidArgumentException('The calculator expected three arguments.');
}

if (!is_numeric($operators[0]) || !is_numeric($operators[2])) {
    throw new InvalidArgumentException('The first and the third arguments must be numeric.');
}

echo 'Result: ' . $calc->calculate(floatval($operators[0]), floatval($operators[2]), $operators[1]) . PHP_EOL;