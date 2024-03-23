<?php

declare(strict_types=1);

class QueryParams extends AbstractQueryParams implements QueryParamsInterface
{
    public function __construct(Mainquery $query, StatementFactory $stFactory, QueryParamsHelper $helper)
    {
        parent::__construct($query, $stFactory, $helper);
    }

    public function raw(string $query): self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        ! isset($this->rawStatus) ? $this->rawStatus = true : '';
        if (! isset($query) | empty($query)) {
            throw new BadQueryArgumentException('Invalid Query');
        }
        $this->currentTable = $query;
        $this->add([
            'tbl' => $query,
        ], __FUNCTION__);
        return $this;
    }

    public function select(array|string ...$selectors) : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        ! isset($this->selectStatus) ? $this->selectStatus = true : '';
        if (! isset($this->select)) {
            $this->select = StatementFactory::create(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        $this->fields($selectors);
        return $this;
    }

    /** @inheritDoc */
    public function insert(array|Entity|CollectionInterface ...$data) : self
    {
        $this->dataChecking($data);
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        if (! isset($this->insert)) {
            $this->insert = StatementFactory::create(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        $this->insert->add(new QueryParameters([
            'tbl' => $this->currentTable,
        ], __FUNCTION__, __FUNCTION__, $this->queryType->name));

        $this->insertStatus = true;
        return $this;
    }

    public function update(string|array|Entity|CollectionInterface ...$data) : self
    {
        $args = func_get_args();
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        list($this->params, $this->method, $baseMethod) = $this->params($args, __FUNCTION__);
        $this->dataChecking($this->params);

        $this->update->add(new QueryParameters([
            'tbl' => $this->currentTable,
        ], $this->method, $baseMethod, $this->queryType->name));
        $this->updateStatus = true;
        return $this;
    }

    public function updateWithCte(string|array|Entity|CollectionInterface ...$data) : self
    {
        $this->dataChecking($data);
        return $this->withCte();
    }

    public function into(string $tbl) : self
    {
        if (! is_string($tbl)) {
            throw new BadQueryArgumentException('Please provide a table as a string');
        }
        if ($tbl !== $this->entity->table()) {
            $entity = str_replace(' ', '', ucwords(str_replace('_', ' ', $tbl))) . 'Entity';
            if (! class_exists($entity)) {
                throw new BadQueryArgumentException('This table does not exist or the Entity is not set.');
            }
            $this->entity = Application::diget($entity);
            $this->entity->assign(array_combine($this->columns, $this->arr_values));
            $this->currentTable = $this->entity->table();
        }
        if (null !== $tbl) {
            $this->updateSelectorsOnSpecifiedTable($tbl);
        }
        return $this;
    }

    public function values(...$values) : self
    {
        if (is_array($values) && ArrayUtil::isAssoc($values)) {
            throw new BadQueryArgumentException('You must provide a list of values');
        }
        if (ArrayUtil::isMultidimentional($values)) {
            $this->checkClolumnsOnEntity();
            $collection = new Collection();
            foreach ($values as $entry) {
                $this->checkForInvalidValues($entry);
                $arr_values = array_combine($this->columns, $entry);
                $collection->add($this->entity->assign($arr_values));
                $this->arr_values[] = $arr_values;
            }
            $this->entity = $collection;
            $this->currentTable = $collection->first()->table();
        } else {
            if (! empty($this->columns)) {
                $values = ! empty($values) ? $values : $this->arr_values;
                if (! empty($values)) {
                    $this->checkClolumnsOnEntity();
                    $this->checkForInvalidValues($values);
                    $this->arr_values = array_combine($this->columns, $values);
                    $this->entity->assign($this->arr_values);
                }
            }
        }
        if (! isset($this->entity)) {
            throw new BadQueryArgumentException('No Data to insert');
        }
        if (! isset($this->values)) {
            $this->values = StatementFactory::create(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        $params = [
            'tbl' => $this->currentTable,
            'values' => $this->arr_values,
            'entity' => $this->entity,
        ];
        $this->values->add(new ValuesParameters($params, __FUNCTION__, __FUNCTION__, $this->queryType->name));
        $this->valuesSatatus = true;
        return $this;
    }

    public function delete(null|string|Entity $tbl = null) : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        if ($tbl instanceof Entity) {
            $entity = $tbl;
            $this->entity = $tbl;
            $this->currentTable = strtolower(trim(str_replace('Entity', '', $entity::class)));
            $this->dataFromEntities = true;
        } elseif (null !== $tbl && is_string($tbl)) {
            $this->currentTable = $tbl;
        }
        if (! isset($this->deleteStatus)) {
            $this->add([
                'tbl' => $this->currentTable,
            ], __FUNCTION__, );
        }
        $this->deleteStatus = true;
        return $this;
    }

    public function set(array $setParams = []) : self
    {
        if (! ArrayUtil::isAssoc($setParams)) {
            throw new BadQueryArgumentException('you must set an array with field/value pair to update the table');
        }
        $this->columns = array_keys($setParams);
        $this->arr_values = ArrayUtil::valuesFromArray($setParams);
        $this->values();
        $this->setStatus = true;
        return $this;
    }

    public function table(mixed $tbl) : self
    {
        if ($this->currentTable !== $tbl) {
            $this->updateSelectorsOnSpecifiedTable($tbl);
        }
        return $this;
    }

    public function from(?string $tbl = null) : self
    {
        $this->fromStatus = true;
        null !== $tbl ? $this->currentTable = $tbl : '';
        if (null !== $tbl) {
            $this->updateSelectorsOnSpecifiedTable($this->currentTable);
        }
        if (! in_array($this->queryType->name, ['DELETE', 'UPDATE'])) {
            $this->add([
                'tbl' => $tbl ?? $this->currentTable,
            ], __FUNCTION__, );
        }
        return $this;
    }

    public function join(string $tbl, string|array|Closure ...$selectors) : self
    {
        $this->checkQuery();
        $args = $this->joinParams($selectors, $tbl, __FUNCTION__);
        list($this->params, $method, $baseMethod) = $this->params($args, __FUNCTION__);
        $this->joinTable = $tbl;
        ! empty($this->params['selectors']) ? $this->fields($this->params['selectors'], $this->entity($tbl), __FUNCTION__) : '';

        $join = $this->stmtFactory->getStatementObj($method, $baseMethod, $this->join);

        $join->add(new QueryParameters($args, $method, $baseMethod, $this->queryType->name));

        $this->join->add($join);
        $this->lastMethod = $baseMethod;

        return $this;
    }

    // public function joinOn(string $tbl, string $baseMethod, AbstractQueryStatement $parent) : AbstractQueryStatement
    // {
    //     $method = strtolower(substr(__FUNCTION__, strpos(__FUNCTION__, 'On')));
    //     $stmtObj = $this->stmtFactory->getStatementObj($method, 'join', $parent);
    //     $this->addParameters($stmtObj, $tbl, 'on', $method, $baseMethod);
    //     return $stmtObj;
    // }

    public function on(int|string|array|Closure ...$onConditions) : self
    {
        $this->addCondition($onConditions, __FUNCTION__);
        return $this;
    }

    public function leftJoin(string $tbl, string|array|Closure ...$selectors) : self
    {
        if (ArrayUtil::isMultidimentional($selectors)) {
            $selectors = $selectors[0];
        }
        $args = [
            'selectors' => $selectors,
            'method' => __FUNCTION__,
        ];
        return $this->join($tbl, $args);
    }

    public function rightJoin(string $tbl, string|array|Closure ...$selectors) : self
    {
        if (ArrayUtil::isMultidimentional($selectors)) {
            $selectors = $selectors[0];
        }
        $args = [
            'selectors' => $selectors,
            'method' => __FUNCTION__,
        ];
        return $this->join($tbl, $args);
    }

    public function where(...$conditions) : self
    {
        $this->checkQuery();
        if (isset($this->with)) {
            $this->params = $conditions;
        } else {
            $this->addCondition($conditions, __FUNCTION__);
        }
        $this->whereStatus = true;
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
        if (count($conditions) == 2) {
            $conditions = ['field' => $conditions[0], 'list' => $conditions[1]];
            return $this->where($conditions, __FUNCTION__);
        }
        throw new BadQueryArgumentException('Bad argumenets in condition ' . __FUNCTION__);
    }

    public function whereNotIn(...$conditions) : self
    {
        if (count($conditions) == 2) {
            $conditions = ['field' => $conditions[0], 'list' => $conditions[1]];
            return $this->where($conditions, __FUNCTION__);
        }
        throw new BadQueryArgumentException('Bad argumenets in condition ' . __FUNCTION__);
    }

    public function having(...$havingConditions) : self
    {
        $args = func_get_args();
        $this->addCondition($args, __FUNCTION__);
        return $this;
    }

    public function havingNotIn(...$havingConditions) : self
    {
        if (count($havingConditions) == 2) {
            $conditions = ['field' => $havingConditions[0], 'list' => $havingConditions[1]];
            return $this->having($conditions, __FUNCTION__);
        }
        throw new BadQueryArgumentException('Bad argumenets in condition ' . __FUNCTION__);
    }

    public function groupBy(...$groupByParams) : self
    {
        $args = $this->parameters($groupByParams, __FUNCTION__);
        $this->addStatement($args, __FUNCTION__);
        return $this;
    }

    public function orderBy(...$orderByParams) : self
    {
        $args = $this->parameters($orderByParams, __FUNCTION__);
        $this->addStatement($args, __FUNCTION__);
        return $this;
    }

    public function limit(int|null $limit = null) : self
    {
        $args = $this->parameters([$limit], __FUNCTION__);
        $this->addStatement($args, __FUNCTION__);
        return $this;
    }

    public function offset(int|null $offset = null) : self
    {
        $args = $this->parameters([$offset], __FUNCTION__);
        $this->addStatement($args, __FUNCTION__);
        return $this;
    }

    public function get() : CollectionInterface
    {
        return new Collection($this->queryParams);
    }

    public function build() : self
    {
        $this->checkQuery();
        $fw = [];
        $flows = $this->queryType->getFlow();
        foreach ($flows as $flow) {
            if (isset($this->{$flow})) {
                $this->query->add($this->{$flow});
                $fw[] = $flow;
            }
        }
        if (isset($this->with)) {
            $this->query->add($this->cte());
        }
        if (in_array($this->queryType->name, ['UPDATE', 'DELETE']) && ! isset($this->where)) {
            $condition = $this->entity->getIdCondition();
            if ($condition) {
                $this->where($condition);
                $this->query->add($this->where);
            }
        }
        return $this;
    }

    public function go() : self
    {
        return $this->build();
    }

    public function return(string $str) : self
    {
        $this->key('options');
        $this->queryParams['options']['return_mode'] = $str;
        return $this->build();
    }

    public function setBaseOptions(string $tbl, Entity $entity) : self
    {
        $this->reset();
        $this->currentTable = $tbl;
        $this->entity = $entity;
        return $this;
    }

    public function query(?string $queryType = null, ...$params) : self|CollectionInterface
    {
        $params = ArrayUtil::flatten_with_keys($params);
        switch ($queryType) {
            case 'select':
                return $this->select($params);
                break;

            case 'insert':
                return $this->insert($params);
                break;
            default:
                return $this;
                break;
        }
    }

    protected function fields(array $columns = [], ?Entity $entity = null, ?string $baseMethod = 'select') : self
    {
        if (! is_array($columns)) {
            throw new BadQueryArgumentException('You must provide an array of fields');
        }
        if (! empty($columns)) {
            $this->columns[] = $columns;
        }
        $tbl = 'select' !== $baseMethod ? $this->joinTable : $this->currentTable;

        if (! isset($this->fields)) {
            $this->fields = StatementFactory::create(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        $this->fields->add(new FieldsParameters([
            'fields' => $columns,
            'entity' => null == $entity ? $this->entity : $entity,
            'tbl' => $tbl,
        ], __FUNCTION__, __FUNCTION__, $this->queryType->name));

        $this->fieldStatus = true;

        return $this;
    }

    protected function key(?string $key = null, ?string $baseKey = null) : void
    {
        if ($baseKey !== null) {
            if (! array_key_exists($baseKey, $this->queryParams)) {
                $this->queryParams[$baseKey] = [];
            }
            if (! array_key_exists($key, $this->queryParams[$baseKey])) {
                $this->queryParams[$baseKey][$key] = [];
            }
        } elseif (! array_key_exists($key, $this->queryParams)) {
            $this->queryParams[$key] = [];
        }
    }

    private function joinParams(array $selectors, string $tbl, string $baseMth) : array
    {
        $method = isset($selectors[0]) && is_array($selectors[0]) && array_key_exists('method', $selectors[0]) ? $selectors[0]['method'] : $baseMth;
        $selectors = isset($selectors[0]) && is_array($selectors[0]) && array_key_exists('selectors', $selectors[0]) ? $selectors[0]['selectors'] : $selectors;

        return [
            'tbl' => $tbl,
            'selectors' => $selectors,
            'method' => $method,
            'baseMethod' => $baseMth,
        ];
    }

    private function entity(string $tbl) : Entity
    {
        $parts = explode('|', $tbl);
        if (count($parts) == 2) {
            $tbl = $parts[0];
        }
        $entityString = str_replace(' ', '', ucwords(str_replace('_', ' ', $tbl))) . 'Entity';
        if (class_exists($entityString)) {
            $entity = Application::diGet($entityString);
            if ($tbl !== $entity->table()) {
                throw new BadQueryArgumentException("Table $tbl does not exist");
            }
            return $entity;
        }
        throw new BadQueryArgumentException("Table $tbl does not exist");
    }

    private function withCte() : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';

        if (! isset($this->with)) {
            $this->with = StatementFactory::create(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        $this->with->add(new QueryParameters([
            'tbl' => $this->cteTable,
            'entity' => $this->entity,
        ], __FUNCTION__, __FUNCTION__, $this->queryType->name));
        return $this;
    }

    private function updateCte(array|Entity|CollectionInterface $data) : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        return $this->update($data, __FUNCTION__);
    }

    private function cte() : AbstractQueryStatement
    {
        $query = new MainQuery(__FUNCTION__, __FUNCTION__);
        $condition = $this->entity instanceof CollectionInterface ? $this->entity->first()->getIdCondition() : $this->entity->getIdCondition();
        $queryParams = new self(Application::diGet(MainQuery::class), $this->stmtFactory, $this->helper);
        $queryParams->setCurrentTable($this->currentTable);
        $closure = function () use ($condition, $queryParams) {
            $query = $queryParams->updateCte($this->entity)->join($this->cteTable, $condition);
            if (isset($this->whereStatus)) {
                $query->where($this->params);
            }
            return $query->go();
        };

        return $query->setMethod($closure);
    }

    private function checkForInvalidValues(array $entry) : void
    {
        if (! empty($this->columns) && (count($this->columns) !== count($entry))) {
            throw new BadQueryArgumentException('You must provide the same number of colums and values');
        }
        if (ArrayUtil::isAssoc($this->columns) | ArrayUtil::isAssoc($entry)) {
            throw new BadQueryArgumentException('You must enter a list of columns and a list of value');
        }
    }

    private function checkClolumnsOnEntity() : void
    {
        foreach ($this->columns as $field) {
            if (! $this->entity->exists($field)) {
                throw new BadQueryArgumentException("Column $field does not exist in table {$this->entity->table()}");
            }
        }
    }

    private function dataChecking(array|Entity|CollectionInterface $data = []) : void
    {
        if (! is_array($data) && ! $data instanceof Entity && ! $data instanceof CollectionInterface) {
            throw new BadQueryArgumentException('You must provide an array, an Entity or a collection of entities');
        }
        if (is_array($data) && count($data) > 1) {
            $collection = new Collection();
            foreach ($data as $entry) {
                if (is_array($entry) && ArrayUtil::isAssoc($entry)) {
                    $en = $this->entity->assign($entry);
                    $collection->add($en);
                } elseif ($entry instanceof Entity) {
                    $collection->add($entry);
                } else {
                    throw new BadQueryArgumentException('You must provide an array of Key/values pairs to insert in the database');
                }
            }
            $this->entity = $collection;
            $this->currentTable = $collection->first()->table();
        } elseif (is_array($data) && count($data) == 1) {
            $data = $data[0];
            if (is_array($data)) {
                if (ArrayUtil::isAssoc($data)) {
                    if (! $this->entity->assign($data)) {
                        $this->columns = array_keys($data);
                        $this->arr_values = ArrayUtil::valuesFromArray($data);
                    }
                } else {
                    throw new BadQueryArgumentException('You must provide an array of Key/values pairs, an entity or a collection of entities to insert in the database');
                }
            } elseif ($data instanceof Entity | $data instanceof CollectionInterface) {
                $this->entity = $data;
                $this->currentTable = $data instanceof CollectionInterface ? $data->first()->table() : $this->entity->table();
            }
        } elseif ($data instanceof Entity | $data instanceof CollectionInterface) {
            $this->entity = $data;
            $this->currentTable = $data instanceof CollectionInterface ? $data->first()->table() : $this->entity->table();
        }
    }

    private function reset()
    {
        $clean = Application::diGet(self::class);
        foreach ($this as $key => $val) {
            if (isset($clean->$key)) {
                $this->$key = $clean->$key;
            } else {
                unset($this->$key);
            }
        }
    }

    private function parameters(array $params, string $method): array
    {
        return [
            'tbl' => $this->currentTable,
            'data' => $params,
            'method' => $meth ?? $method,
            'baseMethod' => $method,
        ];
    }

    private function checkQuery() : void
    {
        if (! isset($this->queryType)) {
            $this->queryType = QueryType::get('select');
        }
        if ($this->queryType->name == 'SELECT') {
            if (! isset($this->selectStatus)) {
                $this->select();
            }
            if (! isset($this->fieldStatus)) {
                $this->fields();
            }
            if (! isset($this->fromStatus)) {
                $this->from();
            }
        } elseif ($this->queryType->name == 'INSERT') {
            if (! isset($this->insertStatus)) {
                $this->insert();
            }
            if (! isset($this->fieldStatus)) {
                $this->fields();
            }
            if (! isset($this->valuesSatatus)) {
                $this->values();
            }
        } elseif (in_array($this->queryType->name, ['UPDATE', 'UPDATECTE'])) {
            if (! isset($this->updateStatus)) {
                $this->update();
            }
            // if (! isset($this->fieldStatus)) {
            //     $this->fields();
            // }
            if (! isset($this->valuesSatatus)) {
                $this->values();
            }
        } elseif ($this->queryType->name == 'DELETE') {
            if (! isset($this->fromStatus)) {
                $this->from();
            }
        } elseif ($this->queryType->name == 'WITHCTE') {
            if (! isset($this->fieldStatus)) {
                $this->fields();
            }
            if (! isset($this->valuesSatatus)) {
                $this->values();
            }
        }
    }

    private function updateSelectorsOnSpecifiedTable(string $tbl) : void
    {
        $statements = $this->currentStatements();
        $entity = $this->entity($tbl);
        foreach ($statements as $stmt) {
            $childrens = $this->$stmt->getChildren()->all();
            foreach ($childrens as $children) {
                if ($children->getTbl() !== $tbl) {
                    $newChildren = $children->setTbl($tbl);
                    $params = $children->getParams();
                    $params['tbl'] = $tbl;
                    $params['entity'] = $entity;
                    $newChildren->setParams($params);
                    $newChildren->setEntity($entity);
                    $this->$stmt->getChildren()->updateValue($children, $newChildren);
                }
            }
        }
    }

    private function currentStatements() : array
    {
        $st = [];
        foreach ($this as $key => $value) {
            if (isset($this->$key) && $this->$key instanceof AbstractQueryStatement) {
                $key !== 'query' ? $st[] = $key : '';
            }
        }
        return $st;
    }
}