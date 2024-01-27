<?php

declare(strict_types=1);

interface QueryParamsNewInterface
{
    public function select(?string $tbl = null, ...$selectors) : self;

    public function from(?string $tbl = null) : self;

    public function table(?string $tbl = null) : self;

    public function join(?string $tbl = null, ...$selectors) : self;

    public function leftJoin(?string $tbl = null, ...$selectors) : self;

    public function rightJoin(?string $tbl = null, ...$selectors) : self;

    public function on(...$onConditions) : self;

    public function where(...$conditions) : self;

    public function orWhere(...$conditions) : self;

    public function andWhere(...$conditions) : self;

    public function whereIn(...$conditions) : self;

    public function whereNotIn(...$conditions) : self;

    public function having(...$havingConditions) : self;

    public function groupBy(...$groupByParams) : self;

    public function orderBy(...$orderByParams) : self;

    public function limit(int|null $limit = null) : self;

    public function offset(int|null $offset = null) : self;

    public function insert(?string $tbl = null, ...$params) : self;

    public function return(string $str) : self;

    public function get() : CollectionInterface;

    public function build() : self;

    public function query(?string $queryType = null, ...$params) : self|CollectionInterface;

    public function setBaseOptions(string $tbl, Entity $entity) : self;

    public function getSelect(): SelectStatement;

    public function setSelect(SelectStatement $select): AbstractQueryParamsNew;

    public function getFrom(): TableStatement;

    public function setFrom(TableStatement $from): AbstractQueryParamsNew;

    public function getJoin(): TableStatement;

    public function setJoin(TableStatement $join): AbstractQueryParamsNew;

    public function getWhere(): ConditionStatement;

    public function setWhere(ConditionStatement $where): AbstractQueryParamsNew;

    public function getOn(): ConditionStatement;

    public function setOn(ConditionStatement $on): AbstractQueryParamsNew;

    public function getHaving(): ConditionStatement;

    public function setHaving(ConditionStatement $having): AbstractQueryParamsNew;

    public function getGroupBy(): GroupAndSortStatement;

    public function setGroupBy(GroupAndSortStatement $groupBy): AbstractQueryParamsNew;

    public function getOrderBy(): GroupAndSortStatement;

    public function setOrderBy(GroupAndSortStatement $orderBy): AbstractQueryParamsNew;

    public function getLimit(): CounterStatement;

    public function setLimit(CounterStatement $limit): AbstractQueryParamsNew;

    public function getOffset(): CounterStatement;

    public function setOffset(CounterStatement $offset): AbstractQueryParamsNew;

    public function getQueryParams(): array;

    public function setQueryParams(array $queryParams): AbstractQueryParamsNew;

    public function getCurrentTable(): string;

    public function setCurrentTable(string $currentTable): AbstractQueryParamsNew;

    public function getTableAlias(): array;
}