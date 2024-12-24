<?php

namespace PhpProPractice\App\Calculator;

use PhpProPractice\App\Calculator\Actions\Interfaces\IAction;
use PhpProPractice\App\Calculator\Interfaces\ICalculator;

class Calculator implements ICalculator
{
    /**
     * @var IAction[]
     */
    protected array $registeredActions = [];

    public function registerAction(IAction $action): static
    {
        if (isset($this->registeredActions[$action->getMark()])) {
            throw new \InvalidArgumentException("Action with mark '{$action->getMark()}' already exists.");
        }

        $this->registeredActions[$action->getMark()] = $action;

        return $this;
    }

    /**
     * @param IAction[] $actions
     */
    public function registerActions(array $actions): static
    {
        foreach ($actions as $action) {
            $this->registerAction($action);
        }

        return $this;
    }

    public function calculate(int|float $a, int|float $b, string $mark): int|float
    {
        if (!isset($this->registeredActions[$mark])) {
            throw new \InvalidArgumentException("AbstractAction with mark '{$mark}' is not registered.");
        }

        return $this->registeredActions[$mark]->execute($a, $b);
    }

    public function getAvailableMarks(): array
    {
        return array_keys($this->registeredActions);
    }
}