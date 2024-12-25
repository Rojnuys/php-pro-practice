<?php

namespace PhpProPractice\App\Calculator\Actions;

class Sub extends AbstractAction
{
    protected const string MARK = '-';

    public function execute(float|int $a, float|int $b): int|float
    {
        return $a - $b;
    }
}