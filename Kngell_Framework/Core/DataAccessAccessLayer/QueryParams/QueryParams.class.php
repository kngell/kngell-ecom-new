<?php

declare(strict_types=1);

class QueryParams extends AbstractQueryParams implements QueryParamsInterface
{
    private const SELECT_FLOW = ['select', 'from', 'join', 'where', 'groupBy', 'orderBy', 'limit', 'offset'];

    public function __construct(MainQuery $query, QueryParamsHelper $helper, Token $token, StatementFactory $stFactory)
    {
        parent::__construct($query, $helper, $token, $stFactory);
    }

    public function rawQuery(string $query): self
    {
        $this->statementProcessing([
            'tbl' => $tbl ?? $this->currentTable, 'alias' => $this->alias,
        ], __FUNCTION__, 'raw');
        return $this;
    }

    public function select(?string $tbl = null, ...$selectors) : self
    {
        $this->queryParams['queryType'] = 'select';
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        $this->selectStatus = true;
        $this->statementProcessing([
            'table' => $tbl,
            'alias' => $alias,
            'selectors' => $selectors,
        ], __FUNCTION__, 'select');
        if (! $this->fromStatus) {
            $this->from();
        }
        return $this;
    }

    public function table(?string $tbl = null) : self
    {
        return $this;
    }

    public function from(?string $tbl = null) : self
    {
        $this->fromStatus = true;
        $this->statementProcessing([
            'tbl' => $tbl ?? $this->currentTable, 'alias' => $this->alias,
        ], __FUNCTION__, 'from');
        return $this;
    }

    public function join(?string $tbl = null, ...$selectors) : self
    {
        if (null == $tbl) {
            throw new BadQueryArgumentException('No Join table to Define!');
        }
        if (! $this->selectStatus) {
            $this->select();
        }
        if (! $this->fromStatus) {
            $this->from();
        }
        list($alias, $tbl) = $this->tableAlias($tbl);
        $this->joinTable = $tbl;
        $this->statementProcessing([
            'table' => $tbl,
            'alias' => $alias,
            'selectors' => $selectors,
            'rule' => $this->onRule,
        ], 'select', 'select');
        $this->statementProcessing(
            [
                'tbl' => $tbl,
                'alias' => $alias,
                'joinRule' => $this->onRule,
            ],
            __FUNCTION__,
            'join'
        );
        $this->onRule = 'INNER JOIN';
        return $this;
    }

    public function leftJoin(?string $tbl = null, ...$selectors) : self
    {
        $this->onRule = 'LEFT JOIN';
        return $this->join($tbl, $selectors);
    }

    public function rightJoin(?string $tbl = null, ...$selectors) : self
    {
        $this->onRule = 'RIGHT JOIN';
        return $this->join($tbl, $selectors);
    }

    public function on(...$onConditions) : self
    {
        $args = func_get_args();
        $this->conditionsProcessing($args, __FUNCTION__, $this->joinTable, $this->alias);
        return $this;
    }

    public function where(...$conditions) : self
    {
        $args = func_get_args();
        $this->conditionsProcessing($args, __FUNCTION__, $this->currentTable, $this->alias);
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
        $conditions = $this->inNotinConditions($conditions);
        return $this->where($conditions, __FUNCTION__);
    }

    public function whereNotIn(...$conditions) : self
    {
        $conditions = $this->inNotinConditions($conditions);
        return $this->where($conditions, __FUNCTION__);
    }

    public function having(...$havingConditions) : self
    {
        $args = func_get_args();
        $this->conditionsProcessing($args, __FUNCTION__, $this->currentTable, $this->alias);
        return $this;
    }

    public function havingNotIn(...$havingConditions) : self
    {
        if (count($havingConditions) == 2) {
            $conditions = array_merge([$havingConditions[0]], $havingConditions[1]);
            return $this->having($conditions, __FUNCTION__);
        }
        throw new BadQueryArgumentException('Bad Not In Argumenets or clause');
    }

    public function groupBy(...$groupByParams) : self
    {
        $this->statementProcessing(
            [
                'params' => $groupByParams,
                'tblAlias' => $this->tableAlias,
            ],
            __FUNCTION__,
            'groupBy'
        );
        return $this;
    }

    public function orderBy(...$orderByParams) : self
    {
        $this->statementProcessing(
            [
                'params' => $orderByParams,
                'tblAlias' => $this->tableAlias,
            ],
            __FUNCTION__,
            'orderBy'
        );
        return $this;
    }

    public function limit(int|null $limit = null) : self
    {
        $this->statementProcessing(
            [
                'params' => $limit,
                'tblAlias' => $this->tableAlias,
            ],
            __FUNCTION__,
            'limit'
        );
        return $this;
    }

    public function offset(int|null $offset = null) : self
    {
        $this->statementProcessing(
            [
                'params' => $offset,
                'tblAlias' => $this->tableAlias,
            ],
            __FUNCTION__,
            'offset'
        );
        return $this;
    }

    public function insert(?string $tbl = null, ...$params) : self
    {
        $this->queryParams['queryType'] = 'insert';
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        $this->method = __FUNCTION__;
        $this->key('table');
        $this->queryParams['insert']['table'] = $tbl;
        $this->queryParams['insert']['tablealias'] = $alias;
        if (isset($params)) {
            return $this->parseFieldsValuesForInsert($params);
        }
        return $this;
    }

    public function get() : CollectionInterface
    {
        return new Collection($this->queryParams);
    }

    public function build() : self
    {
        if (! $this->selectStatus) {
            $this->select();
        }
        if (! $this->fromStatus) {
            $this->from();
        }
        if ($this->queryParams['queryType'] == 'select') {
            foreach (self::SELECT_FLOW as $flow) {
                if (isset($this->{$flow})) {
                    $this->query->add($this->{$flow});
                }
            }
        }
        [$query,$params,$bind_arr] = $this->query->proceed();
        return $this;
    }

    public function return(string $str) : self
    {
        $this->key('options');
        $this->queryParams['options']['return_mode'] = $str;
        return $this->build();
    }

    public function setBaseOptions(string $tbl, Entity $entity) : self
    {
        $this->currentTable = $tbl;
        return $this;
    }

    public function query(?string $queryType = null, ...$params) : self|CollectionInterface
    {
        $params = ArrayUtil::flatten_with_keys($params);
        switch ($queryType) {
            case 'select':
                return $this->select(null, $params);
                break;

            case 'insert':
                return $this->insert($this->currentTable, $params)
                    ->parseFieldsValuesForInsert($params);
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
}