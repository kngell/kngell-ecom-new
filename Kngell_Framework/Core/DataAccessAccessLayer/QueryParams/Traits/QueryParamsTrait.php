<?php

declare(strict_types=1);

trait QueryParamsTrait
{
    /**
     * Get the value of helper.
     */
    public function getHelper(): QueryParamsHelper
    {
        return $this->helper;
    }

    /**
     * Set the value of helper.
     */
    public function setHelper(QueryParamsHelper $helper): self
    {
        $this->helper = $helper;

        return $this;
    }

    /**
     * Get the value of select.
     */
    public function getSelect(): SelectStatement
    {
        return $this->select;
    }

    /**
     * Set the value of select.
     */
    public function setSelect(SelectStatement $select): self
    {
        $this->select = $select;

        return $this;
    }

    /**
     * Get the value of from.
     */
    public function getFrom(): QueryStatement
    {
        return $this->from;
    }

    /**
     * Set the value of from.
     */
    public function setFrom(QueryStatement $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the value of join.
     */
    public function getJoin(): QueryStatement
    {
        return $this->join;
    }

    /**
     * Set the value of join.
     */
    public function setJoin(QueryStatement $join): self
    {
        $this->join = $join;

        return $this;
    }

    /**
     * Get the value of where.
     */
    public function getWhere(): ConditionStatement
    {
        return $this->where;
    }

    /**
     * Set the value of where.
     */
    public function setWhere(ConditionStatement $where): self
    {
        $this->where = $where;

        return $this;
    }

    /**
     * Get the value of on.
     */
    public function getOn(): ConditionStatement
    {
        return $this->on;
    }

    /**
     * Set the value of on.
     */
    public function setOn(ConditionStatement $on): self
    {
        $this->on = $on;

        return $this;
    }

    /**
     * Get the value of having.
     */
    public function getHaving(): ConditionStatement
    {
        return $this->having;
    }

    /**
     * Set the value of having.
     */
    public function setHaving(ConditionStatement $having): self
    {
        $this->having = $having;

        return $this;
    }

    /**
     * Get the value of groupBy.
     */
    public function getGroupBy(): GroupAndSortStatement
    {
        return $this->groupBy;
    }

    /**
     * Set the value of groupBy.
     */
    public function setGroupBy(GroupAndSortStatement $groupBy): self
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    /**
     * Get the value of orderBy.
     */
    public function getOrderBy(): GroupAndSortStatement
    {
        return $this->orderBy;
    }

    /**
     * Set the value of orderBy.
     */
    public function setOrderBy(GroupAndSortStatement $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get the value of limit.
     */
    public function getLimit(): CounterStatement
    {
        return $this->limit;
    }

    /**
     * Set the value of limit.
     */
    public function setLimit(CounterStatement $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of offset.
     */
    public function getOffset(): CounterStatement
    {
        return $this->offset;
    }

    /**
     * Set the value of offset.
     */
    public function setOffset(CounterStatement $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get the value of queryParams.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Set the value of queryParams.
     */
    public function setQueryParams(array $queryParams): self
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    /**
     * Get the value of currentTable.
     */
    public function getCurrentTable(): string
    {
        return $this->currentTable;
    }

    /**
     * Set the value of currentTable.
     */
    public function setCurrentTable(string $currentTable): self
    {
        $this->currentTable = $currentTable;

        return $this;
    }

    /**
     * Get the value of alias.
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set the value of alias.
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get the value of method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Set the value of method.
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get the value of tableAlias.
     */
    public function getTableAlias(): array
    {
        return $this->tableAlias;
    }

    /**
     * Set the value of tableAlias.
     */
    public function setTableAlias(array $tableAlias): self
    {
        $this->tableAlias = $tableAlias;

        return $this;
    }

    /**
     * Get the value of aliasCheck.
     */
    public function getAliasCheck(): array
    {
        return $this->aliasCheck;
    }

    /**
     * Set the value of aliasCheck.
     */
    public function setAliasCheck(array $aliasCheck): self
    {
        $this->aliasCheck = $aliasCheck;

        return $this;
    }

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the value of fields.
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields.
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the value of values.
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Set the value of values.
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Get the value of onRule.
     */
    public function getOnRule(): string
    {
        return $this->onRule;
    }

    /**
     * Set the value of onRule.
     */
    public function setOnRule(string $onRule): self
    {
        $this->onRule = $onRule;

        return $this;
    }

    /**
     * Get the value of joinTable.
     */
    public function getJoinTable(): ?string
    {
        return $this->joinTable;
    }

    /**
     * Set the value of joinTable.
     */
    public function setJoinTable(?string $joinTable): self
    {
        $this->joinTable = $joinTable;

        return $this;
    }

    /**
     * Get the value of token.
     */
    public function getToken(): Token
    {
        return $this->token;
    }

    /**
     * Set the value of token.
     */
    public function setToken(Token $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of selectStatus.
     */
    public function isSelectStatus(): bool
    {
        return $this->selectStatus;
    }

    /**
     * Set the value of selectStatus.
     */
    public function setSelectStatus(bool $selectStatus): self
    {
        $this->selectStatus = $selectStatus;

        return $this;
    }

    /**
     * Get the value of fromStatus.
     */
    public function isFromStatus(): bool
    {
        return $this->fromStatus;
    }

    /**
     * Set the value of fromStatus.
     */
    public function setFromStatus(bool $fromStatus): self
    {
        $this->fromStatus = $fromStatus;

        return $this;
    }
}
