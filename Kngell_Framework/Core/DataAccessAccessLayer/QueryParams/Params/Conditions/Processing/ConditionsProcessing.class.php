<?php

declare(strict_types=1);

class ConditionsProcessing extends AbstractConditionsProcessing
{
    public function __construct(CollectionInterface $conditionsList)
    {
        parent::__construct($conditionsList);
    }

    public function proceed(): array
    {
        $condSummary = [];
        $this->conditions = $this->helper->prepareCondition($this->conditions);
        foreach ($this->conditions  as $key => $conditions) {
            if (is_array($conditions)) {
                if (is_string($key) && str_starts_with($key, 'subCond')) {
                    $subCond = (Application::diget(self::class))->setConditions($conditions)
                        ->addClosureState($this->closureState)
                        ->setMethod($this->method)
                        ->setHelper($this->helper)
                        ->setTbl($this->tbl)
                        ->proceed();
                    $condSummary = array_merge($condSummary, $subCond);
                }
                if (count($conditions) == count($conditions, COUNT_RECURSIVE)) {
                    $condSummary[] = $this->condition($conditions, $key, $this->closureState);
                }
            }
        }
        return $condSummary;
    }

    public function add(AbstractConditionsProcessing $conditions): self
    {
        $conditions->level = $this->level + 1;
        $this->conditionsList->add($conditions);
        return $this;
    }
}