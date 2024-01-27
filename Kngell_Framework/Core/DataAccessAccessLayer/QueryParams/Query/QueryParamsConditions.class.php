<?php

declare(strict_types=1);

class QueryParamsConditions extends AbstractQueryParamsNew implements QueryParamsConditionsInterface
{
    public function on(...$onConditions) : self
    {
        if (empty($onConditions)) {
            throw new BadQueryArgumentException('You Must have at least one condition');
        }
        $args = func_get_args();
        if (! isset($this->onConditionObj)) {
            $this->setOnConditionObj(Application::diGet(Conditions::class));
        }
        $conditionObj = $this->conditionsProcessing($this->onConditionObj, $args, __FUNCTION__, 'setOnConditionObj', $this->joinTable, $this->alias);
        $ruleKey = $this->joinnedRuleKey();
        $this->queryParams['joinRules']['tables'][$ruleKey]['on'] = $conditionObj;
        $this->onConditionObj->add($conditionObj);
        return $this;
    }

    public function where(...$conditions) : self
    {
        if (empty($conditions)) {
            throw new BadQueryArgumentException('You Must have at least one condition');
        }
        $args = func_get_args();
        if (! $this->selectStatus) {
            $this->select();
        }
        if (! isset($this->wereConditionObj)) {
            $this->setWereConditionObj(Application::diGet(Conditions::class));
        }
        $conditionObj = $this->conditionsProcessing($this->wereConditionObj, $args, __FUNCTION__, 'setWereConditionObj', $this->currentTable, $this->alias);
        $this->wereConditionObj->add($conditionObj);
        return $this;
    }

    public function orWhere(...$conditions) : self
    {
        return $this->where($conditions, __FUNCTION__);
    }

    public function andWhere(...$conditions) : self
    {
        return $this->where($conditions, __FUNCTION__);
    }

    public function whereIn(...$conditions) : self
    {
        $conditions = $this->inNotinConditions($conditions);
        return $this->where($conditions, __FUNCTION__);
    }

    public function whereNotIn(...$conditions) : self
    {
        $conditions = $this->inNotinConditions($conditions);
        return $this->where($conditions, __FUNCTION__);
    }

    public function having(...$havingConditions) : self
    {
        if (empty($havingConditions)) {
            throw new BadQueryArgumentException('You Must have at least one having condition');
        }
        $args = func_get_args();
        if (! isset($this->havingConditionObj)) {
            $this->setHavingConditionObj(Application::diGet(Conditions::class));
        }
        $conditionObj = $this->conditionsProcessing($this->havingConditionObj, $args, __FUNCTION__, 'setHavingConditionObj', $this->currentTable, $this->alias);
        $this->havingConditionObj->add($conditionObj);
        return $this;
    }

    public function havingNotIn(...$havingConditions) : self
    {
        if (count($havingConditions) == 2) {
            $conditions = array_merge([$havingConditions[0]], $havingConditions[1]);
            return $this->having($conditions, __FUNCTION__);
        }
        throw new BadQueryArgumentException('Bad Not In Argumenets or clause');
    }

    /**
     * Set the value of onConditionObj.
     */
    private function setOnConditionObj(Conditions $onConditionObj): self
    {
        $this->onConditionObj = $onConditionObj;
        $this->onConditionObj->getLevel() == false ? $this->onConditionObj->setLevel(0) : '';
        return $this;
    }
}