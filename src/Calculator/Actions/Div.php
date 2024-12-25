<?php

namespace PhpProPractice\App\Calculator\Actions;

class Div extends AbstractAction
{
    protected const string MARK = '/';

    public function execute(float|int $a, float|int $b): int|float
    {
        try {
            return $a / $b;
        } catch (\DivisionByZeroError) {
            throw new \InvalidArgumentException('Division By Zero.');
        }
    }
}