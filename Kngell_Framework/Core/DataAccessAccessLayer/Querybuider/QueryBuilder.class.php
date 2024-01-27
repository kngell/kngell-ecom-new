<?php

declare(strict_types=1);

class QueryBuilder implements QueryBuilderInterface
{
    private array $aggregate;
    private array $aggField;
    private array $recursiveQuery;
    private array $params = [];
    private array $bindAry = [];
    private ?QueryParamsNewInterface $queryParams;

    public function __construct(?QueryParamsNewInterface $queryParams)
    {
        // parent::__construct($queryParams);
        $this->queryParams = $queryParams;
    }

    public function buildQuery(array $args = []): QueryBuilderInterface
    {
        return $this;
    }

    public function query() : string
    {
        $queryType = $this->queryParams->getQueryParams()['queryType'];
        if ($queryType == 'select') {
            list($query, $queryRecursive) = $this->mainQuery();
            if (isset($this->recursiveQuery)) {
                $query = $this->recursive($queryRecursive, $query);
            }

            return $query;
        }
    }

    public function mainQuery() : array
    {
        list($query, $recursivequery) = $this->recursiveQuery(
            $this->baseQuery() . $this->join()
        );
        $query .= $this->where() . $this->groupBy() . $this->having() . $this->orderBy() . $this->limitOffset();
        return [$query, $recursivequery];
    }

    public function recursiveQuery(string $query) : array
    {
        $q = $query;
        // $sql = 'SELECT ';
        // if (isset($this->recursiveQuery) && array_key_exists('recursive', $this->recursiveQuery['options'])) {
        //     $recursive = $this->recursiveQuery['options'];
        //     if (array_key_exists('COUNT', $recursive) && count($selectors) === 1) {
        //         $sql .= 'p.' . $recursive['field'] . ' FROM ' . $mainTable['name'] . ' p ';
        //         $sql .= 'INNER JOIN cte ON p.' . $recursive['parentID'] . '= cte.' . $recursive['id'] . ')';
        //         $sql .= 'SELECT COUNT(' . $recursive['field'] . ') AS ' . $recursive['AS'] . ' FROM cte;';
        //         return [$q, $sql];
        //     }
        // }
        return [$q, ''];
    }

    public function recursive(string $query, string $globalQuery) : string
    {
        $sql = 'WITH RECURSIVE cte AS (';
        $sql .= '(' . $globalQuery . ') ';
        $sql .= 'UNION ALL ';
        $sql .= $query;
        return $sql;
    }

    /**
     * Get the value of bindAry.
     */
    public function getBindAry(): array
    {
        return $this->bindAry;
    }

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function insert(): bool|string
    {
        return '';
    }

    public function select(): string
    {
        return '';
    }

    public function update(): string
    {
        return '';
    }

    public function delete(): string
    {
        return '';
    }

    public function search(): string|bool
    {
        return '';
    }

    public function customQuery(): string
    {
        return '';
    }

    public function showColumn(): string
    {
        return '';
    }

    public function baseQuery() : string
    {
        try {
            $q = '';
            if (CustomReflector::getInstance()->isInitialized('select', $this->queryParams)) {
                $q .= 'SELECT ';
                [$select] = $this->queryParams->getSelect()->proceed();
                [$from] = $this->queryParams->getFrom()->proceed();
                $q .= $select . $from;
                return $q;
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    public function join() : string
    {
        try {
            $join = '';
            if (CustomReflector::getInstance()->isInitialized('join', $this->queryParams)) {
                [$joinString] = $this->queryParams->getJoin()->proceed();
                $join .= $joinString;
            }
            return $join;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Get the value of queryParams.
     */
    public function getQueryParams(): ?CollectionInterface
    {
        return $this->queryParams;
    }

    /**
     * Set the value of queryParams.
     */
    public function setQueryParams(?QueryParamsNewInterface $queryParams): self
    {
        $this->queryParams = $queryParams;

        return $this;
    }

    private function selectors() : string
    {
        try {
            $joinRules = $this->queryParams->all()['joinRules']['tables'];
            $selectors = $this->queryParams->all()['tables']['selectors'];
            if (! empty($selectors)) {
                if (null == $joinRules) {
                    return implode(', ', $selectors);
                } else {
                    return $this->selectorsForJoinedRules();
                }
            }
            return ' *';
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function selectorsForJoinedRules() : string
    {
        try {
            $selectors = '';
            $tableSelectors = $this->queryParams->all()['joinRules']['tables'];
            foreach ($tableSelectors as $selector) {
                $separator = $selector === end($tableSelectors) ? ' ' : ', ';
                $selectors .= $this->selectorAndTable($selector) . $separator;
            }
            return $selectors;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function selectorAndTable(array $tblRules) : string
    {
        $selectors = $tblRules['selectors'];
        $selectorResults = '';
        foreach ($selectors as $selector) {
            $sep = $selector == end($selectors) ? '' : ', ';
            $selectorResults .= $selector . $sep;
        }
        return $selectorResults;
    }

    private function from() : string
    {
        try {
            $mainTable = $this->queryParams->all()['tables']['mainTable'];
            $alias = $this->queryParams->all()['joinRules']['tables'][0]['alias'];
            if (isset($mainTable)) {
                return 'FROM ' . $mainTable . ' ' . $alias;
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function table(string $tbl) : string
    {
        $parts = explode('|', $tbl);
        if (count($parts) > 1) {
            return $parts[0] . ' ' . $parts[1];
        }
        return $tbl;
    }

    private function on(Conditions $onConditions) : string
    {
        try {
            $on = ' ON ';
            if ($onConditions) {
                list($condition, $params, $bind) = $onConditions->proceed();
                $on .= $condition;
                $bind = ArrayUtil::flatten_with_keys($bind);
                $params = ArrayUtil::flatten_with_keys($params);
                ! empty($params) ? $this->params[__FUNCTION__][] = $params : '';
                ! empty($bind) ? $this->bindAry[__FUNCTION__][] = $bind : '';
            }
            return $on;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function where() : string
    {
        try {
            $where = '';
            if (CustomReflector::getInstance()->isInitialized('where', $this->queryParams)) {
                $where .= ' WHERE ';
                [$condition, $params, $bind] = $this->queryParams->getWhere()->proceed();
                $condition ? $where .= $condition : '';
                $bind = ArrayUtil::flatten_with_keys($bind);
                $params = ArrayUtil::flatten_with_keys($params);
                ! empty($params) ? $this->params[__FUNCTION__][] = $params : '';
                ! empty($bind) ? $this->bindAry[__FUNCTION__][] = $bind : '';
            }
            return $where;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function groupBy() : string
    {
        try {
            $groupBy = '';
            if (CustomReflector::getInstance()->isInitialized('groupBy', $this->queryParams)) {
                $groupBy .= ' GROUP BY ';
                [$groupByStr] = $this->queryParams->getGroupBy()->proceed();
                $groupByStr ? $groupBy .= $groupByStr : '';
            }
            return $groupBy;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function having() : string
    {
        try {
            $havingCond = '';
            if (CustomReflector::getInstance()->isInitialized('having', $this->queryParams)) {
                $havingCond .= ' HAVING ';
                [$condition, $params, $bind] = $this->queryParams->getHaving()->proceed();
                $condition ? $havingCond .= $condition : '';
                $bind = ArrayUtil::flatten_with_keys($bind);
                $params = ArrayUtil::flatten_with_keys($params);
                ! empty($params) ? $this->params[__FUNCTION__][] = $params : '';
                ! empty($bind) ? $this->bindAry[__FUNCTION__][] = $bind : '';
            }
            return $havingCond;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function orderBy() : string
    {
        try {
            $orderBy = '';
            if (CustomReflector::getInstance()->isInitialized('orderBy', $this->queryParams)) {
                $orderBy .= ' ORDER BY ';
                [$orderByStr] = $this->queryParams->getOrderBy()->proceed();
                $orderByStr ? $orderBy .= $orderByStr : '';
            }
            // $odBy = $this->queryParams->all()['orderBy'] ?? [];
            // if (is_array($odBy) && ! empty($odBy)) {
            //     $orderBy .= ' ORDER BY ';
            //     foreach ($odBy as $order) {
            //         $sep = end($odBy) == $order ? '' : ', ';
            //         $orderBy .= $order . $sep;
            //     }
            // }
            return $orderBy;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function limitOffset() : string
    {
        try {
            $limitOffset = '';
            if (CustomReflector::getInstance()->isInitialized('limit', $this->queryParams)) {
                $limitOffset .= ' LIMIT ';
                [$limitStr] = $this->queryParams->getLimit()->proceed();
                $limitOffset .= $limitStr;
            }
            if (CustomReflector::getInstance()->isInitialized('offset', $this->queryParams)) {
                $limitOffset .= ' OFFSET ';
                [$offsetStr] = $this->queryParams->getOffset()->proceed();
                $limitOffset .= $offsetStr;
            }
            // if ($this->queryParams->has('limitAndOffet')) {
            //     $lof = $this->queryParams->all()['limitAndOffet'];
            //     if (is_array($lof) && ! empty($lof)) {
            //         if (isset($lof['limit']) && isset($lof['offset']) && $lof['offset'] != -1) {
            //             $limitOffset .= ' LIMIT :offset, :limit';
            //         }
            //         if (isset($lof['limit']) && ! isset($lof['offset'])) {
            //             $limitOffset .= ' LIMIT :limit';
            //         }
            //     }
            // }
            return $limitOffset;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }
}