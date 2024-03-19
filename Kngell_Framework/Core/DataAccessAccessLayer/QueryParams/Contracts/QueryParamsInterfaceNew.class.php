<?php

declare(strict_types=1);

interface QueryParamsInterfaceNew
{
    public function raw(string $query) : self;

    public function select(array|string ...$selectors) : self;

    public function from(?string $tbl = null) : self;

    public function table(mixed $tbl) : self;

    public function join(string|array|null $tbl, string|array|Closure ...$onConditions) : self;

    public function leftJoin(string|array|null $tbl, string|array|Closure ...$onConditions) : self;

    public function rightJoin(string|array|null $tbl, string|array|Closure ...$onConditions) : self;

    public function on(string|array|Closure ...$onConditions) : self;

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

    /**
     * Insert and array of field/values, collection of entities or an Entity Object.
     *
     * @param array|Entity|CollectionInterface ...$data
     * @return self
     */
    public function insert(array|Entity|CollectionInterface ...$data) : self;

    public function into(string $tbl) : self;

    public function values(...$values) : self;

    public function update(string|array|Entity|CollectionInterface ...$data) : self;

    public function updateWithCte(string|array|Entity|CollectionInterface ...$data) : self;

    public function set(array $setParams = []) : self;

    public function delete(null|string|Entity $tbl = null) : self;

    public function go() : self;

    public function return(string $str) : self;

    public function get() : CollectionInterface;

    public function build() : self;

    public function query(?string $queryType = null, ...$params) : self|CollectionInterface;

    public function setBaseOptions(string $tbl, Entity $entity) : self;

    public function setDataFromEntities(bool $dataFromEntities): AbstractQueryParamsNew;

    public function getQueryType(): ?QueryType;

    public function getSelect(): AbstractQueryStatement;

    public function setSelect(AbstractQueryStatement $select): AbstractQueryParamsNew;

    public function getFrom(): AbstractQueryStatement;

    public function setFrom(AbstractQueryStatement $from): AbstractQueryParamsNew;

    public function getJoin(): AbstractQueryStatement;

    public function setJoin(AbstractQueryStatement $join): AbstractQueryParamsNew;

    public function getWhere(): AbstractQueryStatement;

    public function setWhere(AbstractQueryStatement $where): AbstractQueryParamsNew;

    public function getHaving(): AbstractQueryStatement;

    public function setHaving(AbstractQueryStatement $having): AbstractQueryParamsNew;

    public function getGroupBy(): AbstractQueryStatement;

    public function setGroupBy(AbstractQueryStatement $groupBy): AbstractQueryParamsNew;

    public function getOrderBy(): AbstractQueryStatement;

    public function setOrderBy(AbstractQueryStatement $orderBy): AbstractQueryParamsNew;

    public function getLimit(): AbstractQueryStatement;

    public function setLimit(AbstractQueryStatement $limit): AbstractQueryParamsNew;

    public function getOffset(): AbstractQueryStatement;

    public function setOffset(AbstractQueryStatement $offset): AbstractQueryParamsNew;

    public function getQueryParams(): array;

    public function setQueryParams(array $queryParams): AbstractQueryParamsNew;

    public function getCurrentTable(): string;

    public function setCurrentTable(string $currentTable): AbstractQueryParamsNew;

    public function getTableAlias(): array;

    public function getQuery(): AbstractQueryStatement;
}