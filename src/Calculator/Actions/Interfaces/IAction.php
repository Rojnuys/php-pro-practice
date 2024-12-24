<?php

namespace PhpProPractice\App\Calculator\Actions\Interfaces;

interface IAction {
    public function getMark(): string;

    /**
     * @throws \InvalidArgumentException
     */
    public function execute(int|float $a, int|float $b): int|float;
}