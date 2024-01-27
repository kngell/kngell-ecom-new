<?php

declare(strict_types=1);

class SubConditionsProcessing extends AbstractConditionsProcessing
{
    public function proceed(): array
    {
        $condSummary = [];
        $this->conditions = $this->helper->prepareCondition($this->conditions);
        foreach ($this->conditions  as $key => $conditions) {
            if (is_array($conditions)) {
                $arrKey = current(array_keys($conditions));
                if (is_string($arrKey) && str_starts_with($arrKey, 'subCond')) {
                    (Application::diget(self::class, [current($conditions), $this->method]))->proceed();
                    break;
                }
            } elseif (count($conditions) == count($conditions, COUNT_RECURSIVE)) {
                $condSummary[] = $this->condition($conditions, $key, $this->closureState);
            }
        }
        return $condSummary;
    }

    // public function proceed() : array
    // {
    //     $conditions = [];
    //     foreach ($this->conditions as $key => $condition) {
    //         if (is_array($condition)) {
    //             $arrKey = current(array_keys($condition));
    //             if (is_string($arrKey) && str_starts_with($arrKey, 'subCond')) {
    //                 $conditions = current($condition);
    //                 break;
    //             }
    //         }
    //     }
    //     empty($conditions) ? $conditions = $this->conditions : '';
    //     if (count($conditions) == count($conditions, COUNT_RECURSIVE)) {
    //         $condSummary[] = $this->condition($conditions, 0);
    //     } else {
    //         $conditions = $this->helper->prepareCondition($conditions);
    //         foreach ($conditions  as $key => $condition) {
    //             if (count($condition) == count($condition, COUNT_RECURSIVE)) {
    //                 $condSummary[] = $this->condition($condition, $key, $this->closureState);
    //             }
    //         }
    //     }
    //     //$this->closeCondition();
    //     return $condSummary;
    // }
}