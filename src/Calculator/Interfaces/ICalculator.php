<?php

namespace PhpProPractice\App\Calculator\Interfaces;

interface ICalculator
{
    public function calculate(int|float $a, int|float $b, string $mark): int|float;
}