<?php

declare(strict_types=1);

interface QueryParamsInterface
{
    public function rawQuery(string $query) : self;

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

    public function setSelect(SelectStatement $select): AbstractQueryParams;

    public function getFrom(): QueryStatement;

    public function setFrom(QueryStatement $from): AbstractQueryParams;

    public function getJoin(): QueryStatement;

    public function setJoin(QueryStatement $join): AbstractQueryParams;

    public function getWhere(): ConditionStatement;

    public function setWhere(ConditionStatement $where): AbstractQueryParams;

    public function getOn(): ConditionStatement;

    public function setOn(ConditionStatement $on): AbstractQueryParams;

    public function getHaving(): ConditionStatement;

    public function setHaving(ConditionStatement $having): AbstractQueryParams;

    public function getGroupBy(): GroupAndSortStatement;

    public function setGroupBy(GroupAndSortStatement $groupBy): AbstractQueryParams;

    public function getOrderBy(): GroupAndSortStatement;

    public function setOrderBy(GroupAndSortStatement $orderBy): AbstractQueryParams;

    public function getLimit(): CounterStatement;

    public function setLimit(CounterStatement $limit): AbstractQueryParams;

    public function getOffset(): CounterStatement;

    public function setOffset(CounterStatement $offset): AbstractQueryParams;

    public function getQueryParams(): array;

    public function setQueryParams(array $queryParams): AbstractQueryParams;

    public function getCurrentTable(): string;

    public function setCurrentTable(string $currentTable): AbstractQueryParams;

    public function getTableAlias(): array;
}
