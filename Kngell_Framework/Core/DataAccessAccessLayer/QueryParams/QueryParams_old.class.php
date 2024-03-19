<?php

declare(strict_types=1);

class QueryParams_old extends AbstractQueryParams implements QueryParamsInterface
{
    public function __construct(MainQuery $query, QueryParamsHelper $helper, Token $token, StatementFactory $stFactory)
    {
        parent::__construct($query, $helper, $token, $stFactory);
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
        $this->add($selectors, __FUNCTION__);
        if (! isset($this->fromStatus)) {
            $this->from();
        }
        return $this;
    }

    public function insert(...$columns) : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        if (! isset($this->insert)) {
            $this->insert = $this->stmtFactory->createStatement(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        if ($columns[0] instanceof Entity && $this->dataFromEntities) {
            $this->entity = $columns[0];
            $this->currentTable = strtolower(trim(str_replace('Entity', '', $this->entity::class)));
        } else {
            $this->columns = $columns;
        }
        // list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        // if (! isset($this->insertStatus)) {
        //     $this->add([
        //         'tbl' => $tbl ?? $this->currentTable,
        //         'alias' => $alias,
        //     ], __FUNCTION__, );
        // } else {
        //     $this->add($columns, __FUNCTION__);
        // }
        // $this->columns = $columns;
        $this->insertStatus = true;
        return $this;
    }

    public function update(array|Entity|null $updateParams = null) : self
    {
        ! isset($this->queryType) ? $this->queryType = QueryType::get(__FUNCTION__) : '';
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        if (! isset($this->updateStatus)) {
            $this->add([
                'tbl' => $tbl ?? $this->currentTable,
                'alias' => $alias,
            ], __FUNCTION__, );
        }
        if (is_array($updateParams) && ArrayUtil::isAssoc($updateParams)) {
            $this->updateSet = $updateParams;
        } elseif ($updateParams instanceof Entity || $this->dataFromEntities) {
            $this->entity = $updateParams;
            $this->updateSet = $this->entity->getEntityData();
        } elseif (! is_null($updateParams)) {
            throw new BadQueryArgumentException('Update parameters are invalid.');
        }
        $this->updateStatus = true;
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
        $data = $setParams;
        if (! empty($this->columns) && ! empty($this->arr_values) && (count($this->columns) !== count($this->arr_values))) {
            throw new BadQueryArgumentException('The number of the Fields should be equel to the number of values :' . implode(', ', $this->columns));
        }
        if (empty($setParams) && ! empty($this->columns) && ! empty($this->arr_values)) {
            $data = array_combine($this->columns, $this->arr_values);
        } elseif (empty($setParams) && isset($this->entity)) {
            $data = $this->entity->getEntityData();
            unset($data[0]);
        }

        if (count($data) >= 1) {
            $data = ArrayUtil::multiplesArraysFromAssoc($data);
        }
        $this->addCondition($data, __FUNCTION__);
        $this->setStatus = true;
        return $this;
    }

    public function into(string $tbl) : self
    {
        null !== $tbl ? $this->currentTable = $tbl : '';
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        if (null !== $tbl) {
            $this->updateSelectorsOnSpecifiedTable($alias, $tbl);
        }
        return $this;
    }

    public function fields(...$columns) : self
    {
        if (! empty($columns) && $this->columns !== $columns) {
            $this->columns = $columns;
        } elseif (empty($this->columns) && empty($columns)) {
            $this->columns = $this->entity->getAllAttributes();
            unset($this->columns[0]);
        } elseif (! empty($this->columns) && $this->columns[0] instanceof Entity) {
            $this->columns = array_keys($this->columns[0]->getInitializedAttributes());
        }
        $this->queryType->name == 'INSERT' ? $this->add($this->columns, __FUNCTION__) : '';
        $this->fieldStatus = true;
        return $this;
    }

    public function values(...$values) : self
    {
        if ($this->dataFromEntities) {
            $values = $this->entity->getInitializedAttributes();
        } else {
            $this->arr_values = ! empty($values) ? array_combine($this->columns, $values) : [];
        }
        // if ($this->queryType->name == 'UPDATE' && ! empty($values)) {
        // } elseif ($this->queryType->name == 'INSERT' && ! empty($values)) {

        // }
        $this->addCondition($values, __FUNCTION__);
        $this->valuesSatatus = true;
        return $this;
    }

    public function table(mixed $tbl = null) : self
    {
        if (! isset($this->table)) {
            $this->table = $this->stmtFactory->createStatement(__FUNCTION__, __FUNCTION__, $this->queryType->name, $this->query);
        }
        if (null !== $tbl) {
            list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
            // $this->updateSelectorsOnSpecifiedTable($alias, $tbl);
        }
        $params = [
            'tbl' => $tbl,
            'alias' => $alias,
        ];
        $this->table->add(new QueryParameters($params, __FUNCTION__, __FUNCTION__, $this->queryType->name));
        return $this;
    }

    public function from(?string $tbl = null) : self
    {
        $this->fromStatus = true;
        null !== $tbl ? $this->currentTable = $tbl : '';
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        if (null !== $tbl) {
            $this->updateSelectorsOnSpecifiedTable($alias, $tbl);
        }
        if (! in_array($this->queryType->name, ['DELETE', 'UPDATE'])) {
            $this->add([
                'tbl' => $tbl ?? $this->currentTable,
                'alias' => $alias,
            ], __FUNCTION__, );
        }
        return $this;
    }

    public function join(?string $tbl = null, ...$selectors) : self
    {
        $this->checkQuery();
        $this->initJoinTables($tbl);
        list($alias, $tbl) = $this->tableAlias($tbl);
        $args = [
            'tbl' => $tbl,
            'alias' => $alias,
            'selectors' => is_array($selectors[0]) && array_key_exists('selectors', $selectors[0]) ? $selectors[0]['selectors'] : $selectors,
            'method' => is_array($selectors[0]) && array_key_exists('method', $selectors[0]) ? $selectors[0]['method'] : __FUNCTION__,
            'baseMethod' => __FUNCTION__,
        ];
        $this->joinTable = $tbl;
        $this->select($args)->addStatement($args, __FUNCTION__);
        return $this;
    }

    public function leftJoin(?string $tbl = null, ...$selectors) : self
    {
        $args = [
            'selectors' => $selectors,
            'method' => __FUNCTION__,
        ];
        return $this->join($tbl, $args);
    }

    public function rightJoin(?string $tbl = null, ...$selectors) : self
    {
        $args = [
            'selectors' => $selectors,
            'method' => __FUNCTION__,
        ];
        return $this->join($tbl, $args);
    }

    public function on(...$onConditions) : self
    {
        $this->addCondition($onConditions, __FUNCTION__);
        return $this;
    }

    public function where(...$conditions) : self
    {
        $this->checkQuery();
        $this->addCondition($conditions, __FUNCTION__);
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
        foreach ($this->queryType->getFlow() as $flow) {
            if (isset($this->{$flow})) {
                $this->query->add($this->{$flow});
                $fw[] = $flow;
            }
        }
        if ($this->queryType->name == 'UPDATE' & ! in_array('where', $fw)) {
            throw new BadQueryArgumentException('No where clause in update request.');
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
                return $this->insert($this->currentTable, $params);
                break;
            default:
                return $this;
                break;
        }
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
        $args = $this->helper->normalizeFields($params);
        list($tbl, $data, $meth, $bMeth) = $args;
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        return [
            'tbl' => $tbl,
            'alias' => $alias,
            'data' => $data,
            'tbl_alias' => $this->tableAlias,
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
        } elseif ($this->queryType->name == 'UPDATE') {
            if (! isset($this->setStatus)) {
                $this->set();
            }
        } elseif ($this->queryType->name == 'DELETE') {
            if (! isset($this->fromStatus)) {
                $this->from();
            }
        }
    }

    private function initJoinTables(string $tbl) : void
    {
        if (null == $tbl) {
            throw new BadQueryArgumentException('No Join table to Define!');
        }
        if (! isset($this->selectStatus)) {
            $this->select();
        }
        if (! isset($this->fromStatus)) {
            $this->from();
        }
    }

    private function updateSelectorsOnSpecifiedTable(string $alias, string $tbl) : void
    {
        if (isset($this->{$this->queryType->value})) {
            $childrens = $this->{$this->queryType->value}->getChildren()->all();
            foreach ($childrens as $children) {
                if ($children->getTbl() !== $tbl) {
                    $newChildren = $children->setTbl($tbl);
                    $newChildren->setAlias($alias);
                    $params = $children->getParams();
                    $params['tbl'] = $tbl;
                    $params['alias'] = $alias;
                    $newChildren->setParams($params);
                    $this->{$this->queryType->value}->getChildren()->updateValue($children, $newChildren);
                }
            }
        }
    }

    // private function updateSelectorsOnSpecifiedFields(array $fields) : void
    // {
    //     if (isset($this->{$this->queryType->value})) {
    //         $params = $this->{$this->queryType->value}->getChildren()->first()->getParams();
    //         $params['selectors'] = $fields;
    //         $params = $this->{$this->queryType->value}->getChildren()->first()->setParams($params);
    //     }
    // }
}