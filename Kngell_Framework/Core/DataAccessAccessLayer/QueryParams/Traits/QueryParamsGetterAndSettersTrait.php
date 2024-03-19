<?php

declare(strict_types=1);

trait QueryParamsGetterAndSettersTrait
{
    /**
     * Get the value of queryType.
     */
    public function getQueryType(): ?QueryType
    {
        return $this->queryType;
    }

    /**
     * Set the value of queryType.
     */
    public function setQueryType(?QueryType $queryType): self
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * Get the value of query.
     */
    public function getQuery(): MainQuery
    {
        return $this->query;
    }

    /**
     * Set the value of query.
     */
    public function setQuery(MainQuery $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of stmtFactory.
     */
    public function getStmtFactory(): StatementFactory
    {
        return $this->stmtFactory;
    }

    /**
     * Set the value of stmtFactory.
     */
    public function setStmtFactory(StatementFactory $stmtFactory): self
    {
        $this->stmtFactory = $stmtFactory;

        return $this;
    }

    /**
     * Get the value of select.
     */
    public function getSelect(): AbstractQueryStatement
    {
        return $this->select;
    }

    /**
     * Set the value of select.
     */
    public function setSelect(AbstractQueryStatement $select): self
    {
        $this->select = $select;

        return $this;
    }

    /**
     * Get the value of fields.
     */
    public function getFields(): AbstractQueryStatement
    {
        return $this->fields;
    }

    /**
     * Set the value of fields.
     */
    public function setFields(AbstractQueryStatement $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the value of table.
     */
    public function getTable(): AbstractQueryStatement
    {
        return $this->table;
    }

    /**
     * Set the value of table.
     */
    public function setTable(AbstractQueryStatement $table): self
    {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the value of raw.
     */
    public function getRaw(): AbstractQueryStatement
    {
        return $this->raw;
    }

    /**
     * Set the value of raw.
     */
    public function setRaw(AbstractQueryStatement $raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    /**
     * Get the value of from.
     */
    public function getFrom(): AbstractQueryStatement
    {
        return $this->from;
    }

    /**
     * Set the value of from.
     */
    public function setFrom(AbstractQueryStatement $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the value of join.
     */
    public function getJoin(): AbstractQueryStatement
    {
        return $this->join;
    }

    /**
     * Set the value of join.
     */
    public function setJoin(AbstractQueryStatement $join): self
    {
        $this->join = $join;

        return $this;
    }

    /**
     * Get the value of where.
     */
    public function getWhere(): AbstractQueryStatement
    {
        return $this->where;
    }

    /**
     * Set the value of where.
     */
    public function setWhere(AbstractQueryStatement $where): self
    {
        $this->where = $where;

        return $this;
    }

    /**
     * Get the value of on.
     */
    public function getOn(): AbstractQueryStatement
    {
        return $this->on;
    }

    /**
     * Set the value of on.
     */
    public function setOn(AbstractQueryStatement $on): self
    {
        $this->on = $on;

        return $this;
    }

    /**
     * Get the value of having.
     */
    public function getHaving(): AbstractQueryStatement
    {
        return $this->having;
    }

    /**
     * Set the value of having.
     */
    public function setHaving(AbstractQueryStatement $having): self
    {
        $this->having = $having;

        return $this;
    }

    /**
     * Get the value of set.
     */
    public function getSet(): AbstractQueryStatement
    {
        return $this->set;
    }

    /**
     * Set the value of set.
     */
    public function setSet(AbstractQueryStatement $set): self
    {
        $this->set = $set;

        return $this;
    }

    /**
     * Get the value of groupBy.
     */
    public function getGroupBy(): AbstractQueryStatement
    {
        return $this->groupBy;
    }

    /**
     * Set the value of groupBy.
     */
    public function setGroupBy(AbstractQueryStatement $groupBy): self
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    /**
     * Get the value of orderBy.
     */
    public function getOrderBy(): AbstractQueryStatement
    {
        return $this->orderBy;
    }

    /**
     * Set the value of orderBy.
     */
    public function setOrderBy(AbstractQueryStatement $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * Get the value of limit.
     */
    public function getLimit(): AbstractQueryStatement
    {
        return $this->limit;
    }

    /**
     * Set the value of limit.
     */
    public function setLimit(AbstractQueryStatement $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of offset.
     */
    public function getOffset(): AbstractQueryStatement
    {
        return $this->offset;
    }

    /**
     * Set the value of offset.
     */
    public function setOffset(AbstractQueryStatement $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Get the value of insert.
     */
    public function getInsert(): AbstractQueryStatement
    {
        return $this->insert;
    }

    /**
     * Set the value of insert.
     */
    public function setInsert(AbstractQueryStatement $insert): self
    {
        $this->insert = $insert;

        return $this;
    }

    /**
     * Get the value of update.
     */
    public function getUpdate(): AbstractQueryStatement
    {
        return $this->update;
    }

    /**
     * Set the value of update.
     */
    public function setUpdate(AbstractQueryStatement $update): self
    {
        $this->update = $update;

        return $this;
    }

    /**
     * Get the value of delete.
     */
    public function getDelete(): AbstractQueryStatement
    {
        return $this->delete;
    }

    /**
     * Set the value of delete.
     */
    public function setDelete(AbstractQueryStatement $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    /**
     * Get the value of width.
     */
    public function getWidth(): AbstractQueryStatement
    {
        return $this->width;
    }

    /**
     * Set the value of width.
     */
    public function setWidth(AbstractQueryStatement $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get the value of values.
     */
    public function getValues(): AbstractQueryStatement
    {
        return $this->values;
    }

    /**
     * Set the value of values.
     */
    public function setValues(AbstractQueryStatement $values): self
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Get the value of with.
     */
    public function getWith(): AbstractQueryStatement
    {
        return $this->with;
    }

    /**
     * Set the value of with.
     */
    public function setWith(AbstractQueryStatement $with): self
    {
        $this->with = $with;

        return $this;
    }

    /**
     * Get the value of entity.
     */
    public function getEntity(): Entity|CollectionInterface
    {
        return $this->entity;
    }

    /**
     * Set the value of entity.
     */
    public function setEntity($entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the value of queryClosure.
     */
    public function getQueryClosure(): Closure
    {
        return $this->queryClosure;
    }

    /**
     * Set the value of queryClosure.
     */
    public function setQueryClosure(Closure $queryClosure): self
    {
        $this->queryClosure = $queryClosure;

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
     * Get the value of lastMethod.
     */
    public function getLastMethod(): string
    {
        return $this->lastMethod;
    }

    /**
     * Set the value of lastMethod.
     */
    public function setLastMethod(string $lastMethod): self
    {
        $this->lastMethod = $lastMethod;

        return $this;
    }

    /**
     * Get the value of cteTable.
     */
    public function getCteTable(): string
    {
        return $this->cteTable;
    }

    /**
     * Set the value of cteTable.
     */
    public function setCteTable(string $cteTable): self
    {
        $this->cteTable = $cteTable;

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
    public function getParams(): array|Entity|CollectionInterface
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     */
    public function setParams($params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the value of columns.
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Set the value of columns.
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the value of updateSet.
     */
    public function getUpdateSet(): array
    {
        return $this->updateSet;
    }

    /**
     * Set the value of updateSet.
     */
    public function setUpdateSet(array $updateSet): self
    {
        $this->updateSet = $updateSet;

        return $this;
    }

    /**
     * Get the value of arr_values.
     */
    public function getArrValues(): array
    {
        return $this->arr_values;
    }

    /**
     * Set the value of arr_values.
     */
    public function setArrValues(array $arr_values): self
    {
        $this->arr_values = $arr_values;

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

    /**
     * Get the value of insertStatus.
     */
    public function isInsertStatus(): bool
    {
        return $this->insertStatus;
    }

    /**
     * Set the value of insertStatus.
     */
    public function setInsertStatus(bool $insertStatus): self
    {
        $this->insertStatus = $insertStatus;

        return $this;
    }

    /**
     * Get the value of intoStatus.
     */
    public function isIntoStatus(): bool
    {
        return $this->intoStatus;
    }

    /**
     * Set the value of intoStatus.
     */
    public function setIntoStatus(bool $intoStatus): self
    {
        $this->intoStatus = $intoStatus;

        return $this;
    }

    /**
     * Get the value of fieldStatus.
     */
    public function isFieldStatus(): bool
    {
        return $this->fieldStatus;
    }

    /**
     * Set the value of fieldStatus.
     */
    public function setFieldStatus(bool $fieldStatus): self
    {
        $this->fieldStatus = $fieldStatus;

        return $this;
    }

    /**
     * Get the value of valuesSatatus.
     */
    public function isValuesSatatus(): bool
    {
        return $this->valuesSatatus;
    }

    /**
     * Set the value of valuesSatatus.
     */
    public function setValuesSatatus(bool $valuesSatatus): self
    {
        $this->valuesSatatus = $valuesSatatus;

        return $this;
    }

    /**
     * Get the value of updateStatus.
     */
    public function isUpdateStatus(): bool
    {
        return $this->updateStatus;
    }

    /**
     * Set the value of updateStatus.
     */
    public function setUpdateStatus(bool $updateStatus): self
    {
        $this->updateStatus = $updateStatus;

        return $this;
    }

    /**
     * Get the value of setStatus.
     */
    public function isSetStatus(): bool
    {
        return $this->setStatus;
    }

    /**
     * Set the value of setStatus.
     */
    public function setSetStatus(bool $setStatus): self
    {
        $this->setStatus = $setStatus;

        return $this;
    }

    /**
     * Get the value of deleteStatus.
     */
    public function isDeleteStatus(): bool
    {
        return $this->deleteStatus;
    }

    /**
     * Set the value of deleteStatus.
     */
    public function setDeleteStatus(bool $deleteStatus): self
    {
        $this->deleteStatus = $deleteStatus;

        return $this;
    }

    /**
     * Get the value of rawStatus.
     */
    public function isRawStatus(): bool
    {
        return $this->rawStatus;
    }

    /**
     * Set the value of rawStatus.
     */
    public function setRawStatus(bool $rawStatus): self
    {
        $this->rawStatus = $rawStatus;

        return $this;
    }

    /**
     * Get the value of whereStatus.
     */
    public function isWhereStatus(): bool
    {
        return $this->whereStatus;
    }

    /**
     * Set the value of whereStatus.
     */
    public function setWhereStatus(bool $whereStatus): self
    {
        $this->whereStatus = $whereStatus;

        return $this;
    }

    /**
     * Get the value of dataFromEntities.
     */
    public function isDataFromEntities(): bool
    {
        return $this->dataFromEntities;
    }

    /**
     * Set the value of dataFromEntities.
     */
    public function setDataFromEntities(bool $dataFromEntities): self
    {
        $this->dataFromEntities = $dataFromEntities;

        return $this;
    }

    /**
     * Get the value of stmtObj.
     */
    public function getStmtObj(): AbstractQueryStatement
    {
        return $this->stmtObj;
    }

    /**
     * Set the value of stmtObj.
     */
    public function setStmtObj(AbstractQueryStatement $stmtObj): self
    {
        $this->stmtObj = $stmtObj;

        return $this;
    }
}