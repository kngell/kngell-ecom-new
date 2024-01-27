<?php

declare(strict_types=1);
abstract class AbstractClosureParser
{
    protected array $conditions = [];
    protected int $level;
    protected ?string $method;
    protected ?string $tbl;
    protected QueryConditionsHelper $helper;
    protected AbstractConditions $child;
    protected Conditions $newConditionsObj;

    public function __construct(?string $tbl = null)
    {
        $this->tbl = $tbl;
    }

    abstract public function get(?Conditions $conditionObj = null) : Conditions;

    abstract public function add(self $parser) : bool;

    public function where(...$conditions) : self
    {
        $this->method = __FUNCTION__;
        $this->handle($conditions);
        return $this;
    }

    public function andWhere(...$conditions) : self
    {
        $this->handle($conditions);
        $this->newConditionsObj->add($this->child, $this->method, $this->tbl);
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