<?php

declare(strict_types=1);

interface QueryParamsInterface
{
    public function raw(string $query) : self;

    public function select(array|string ...$selectors) : self;

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

    public function insert(...$columns) : self;

    public function into(string $tbl) : self;

    public function fields(...$columns) : self;

    public function values(...$values) : self;

    public function update(array|Entity|null $updateParams = null) : self;

    public function set(array $setParams = []) : self;

    public function delete(null|string|Entity $tbl = null) : self;

    public function go() : self;

    public function return(string $str) : self;

    public function get() : CollectionInterface;

    public function build() : self;

    public function query(?string $queryType = null, ...$params) : self|CollectionInterface;

    public function setBaseOptions(string $tbl, Entity $entity) : self;

    public function setDataFromEntities(bool $dataFromEntities): AbstractQueryParams;

    public function getQueryType(): ?QueryType;

    public function getSelect(): FieldsStatement;

    public function setSelect(FieldsStatement $select): AbstractQueryParams;

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

    public function getOptions() : array;

    public function setQueryParams(array $queryParams): AbstractQueryParams;

    public function getCurrentTable(): string;

    public function setCurrentTable(string $currentTable): AbstractQueryParams;

    public function getTableAlias(): array;

    public function getQuery(): AbstractQueryStatement;
}