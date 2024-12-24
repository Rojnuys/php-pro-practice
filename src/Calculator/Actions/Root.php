<?php

namespace PhpProPractice\App\Calculator\Actions;

use PhpProPractice\App\Calculator\Actions\Interfaces\IAction;

class Root implements IAction
{
    protected const string MARK = '//';

    public function getMark(): string
    {
        return static::MARK;
    }

    public function execute(int|float $a, int|float $b): int|float
    {
        return $a ** (1 / $b);
    }
}