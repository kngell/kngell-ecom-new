<?php

declare(strict_types=1);

interface QueryParamsInterface
{
    public function raw(string $query) : self;

    public function select(array|string ...$selectors) : self;

    public function from(?string $tbl = null) : self;

    public function table(mixed $tbl) : self;

    public function join(string $tbl, string|array|Closure ...$selectors) : self;

    public function leftJoin(string $tbl, string|array|Closure ...$selectors) : self;

    public function rightJoin(string $tbl, string|array|Closure ...$selectors) : self;

    public function on(int|string|array|Closure ...$onConditions) : self;

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

    public function setDataFromEntities(bool $dataFromEntities): AbstractQueryParams;

    public function getQueryType(): ?QueryType;

    public function getSelect(): AbstractQueryStatement;

    public function setSelect(AbstractQueryStatement $select): AbstractQueryParams;

    public function getFrom(): AbstractQueryStatement;

    public function setFrom(AbstractQueryStatement $from): AbstractQueryParams;

    public function getJoin(): AbstractQueryStatement;

    public function setJoin(AbstractQueryStatement $join): AbstractQueryParams;

    public function getWhere(): AbstractQueryStatement;

    public function setWhere(AbstractQueryStatement $where): AbstractQueryParams;

    public function getHaving(): AbstractQueryStatement;

    public function setHaving(AbstractQueryStatement $having): AbstractQueryParams;

    public function getGroupBy(): AbstractQueryStatement;

    public function setGroupBy(AbstractQueryStatement $groupBy): AbstractQueryParams;

    public function getOrderBy(): AbstractQueryStatement;

    public function setOrderBy(AbstractQueryStatement $orderBy): AbstractQueryParams;

    public function getLimit(): AbstractQueryStatement;

    public function setLimit(AbstractQueryStatement $limit): AbstractQueryParams;

    public function getOffset(): AbstractQueryStatement;

    public function setOffset(AbstractQueryStatement $offset): AbstractQueryParams;

    public function getQueryParams(): array;

    public function getQueryOptions(): array;

    public function setQueryOptions(array $options): AbstractQueryParams;

    public function setQueryParams(array $queryParams): AbstractQueryParams;

    public function getCurrentTable(): string;

    public function setCurrentTable(string $currentTable): AbstractQueryParams;

    public function getTableAlias(): array;

    public function getQuery(): AbstractQueryStatement;
}