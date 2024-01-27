<?php

declare(strict_types=1);
class ClosureParser extends AbstractQueryParamsNew
{
    public function __construct(
        QueryConditionsHelper $helper
    ) {
        parent::__construct($helper);
    }

    public function get(?Conditions $conditionObj = null, int $level = 0) : Conditions
    {
        $childs = $conditionObj->getChildrenStorage()->all();
        $newConditionsObj = new Conditions();
        $newConditionsObj->setLevel($level);
        foreach ($childs as $child) {
            $conditions = $child->getConditions();
            $results = [];
            foreach ($conditions as $key => $condition) {
                if ($condition instanceof Closure) {
                    $condition->__invoke($this);
                    $newConditionsObj->add($this->manageClosure($child, $this->conditions), $this->method, $this->tbl);
                } elseif (is_array($condition)) {
                    $results[] = $condition;
                    $newConditionsObj->add($child->setConditions($condition), $this->method, $this->tbl);
                } else {
                    $results[$key] = $condition;
                    $key == array_key_last($conditions) ? $newConditionsObj->add($child->setConditions($results), $this->method, $this->tbl) : '';
                }
            }
        }
        $conditionObj = $newConditionsObj;
        return $newConditionsObj;
    }

    public function manageClosure(?Conditions $conditionObj, array $conditions) : ?Conditions
    {
        $this->conditions = [];
        foreach ($conditions as $key => $condition) {
            if (is_array($condition) && $condition[0] instanceof Closure) {
                /** @var Closure */
                $closure = $condition[0];
                $closure->__invoke($this);
                $level = $conditionObj->getLevel() + 1;
                $child = new Conditions($this->conditions);
                $child->setLevel($level);
                $conditionObj->add($this->manageClosure($child, $this->conditions));
            } elseif (is_array($condition)) {
                $conditionObj->add(new EndCondition($condition));
            }
        }
        return $conditionObj;
    }

    public function where(...$conditions) : self
    {
        $this->method = __FUNCTION__;
        $this->handle($conditions);
        return $this;
    }

    public function andWhere(...$conditions) : self
    {
        $this->handle($conditions);
        // $this->newConditionsObj->add($this->child, $this->method, $this->tbl);
        $this->method = __FUNCTION__;
        return $this;
    }

    public function orWhere(...$conditions) : self
    {
        $this->handle($conditions);
        $this->method = __FUNCTION__;
        return $this;
    }

    public function on(...$onConditions) : self
    {
        $this->handle($onConditions);
        $this->method = __FUNCTION__;
        return $this;
    }

    public function having(...$havingConditions) : self
    {
        $this->handle($havingConditions);
        $this->method = __FUNCTION__;
        return $this;
    }

    /**
     * Set the value of helper.
     */
    public function setHelper(QueryConditionsHelper $helper): self
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * Get the value of tbl.
     */
    public function getTbl(): ?string
    {
        return $this->tbl;
    }

    /**
     * Set the value of tbl.
     */
    public function setTbl(?string $tbl): self
    {
        $this->tbl = $tbl;

        return $this;
    }

    private function handle(array $whereConditions) : void
    {
        if (empty($whereConditions)) {
            throw new BadQueryArgumentException('You Must have at least one condition');
        }
        array_push($this->conditions, $whereConditions);
    }
}