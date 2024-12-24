<?php

namespace PhpProPractice\App\Calculator\Actions;

use PhpProPractice\App\Calculator\Actions\Interfaces\IAction;

abstract class AbstractAction implements IAction
{
    protected const string MARK = '';

    public function getMark(): string
    {
        return static::MARK;
    }

    public abstract function execute(int|float $a, int|float $b): int|float;
}