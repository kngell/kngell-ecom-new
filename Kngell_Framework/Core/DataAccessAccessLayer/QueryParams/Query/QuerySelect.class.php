<?php

declare(strict_types=1);

class QuerySelect implements QueryInterface
{
    private string $mainTable;
    private array $selectors;
    private array $joinRules;
    private array $groupBy;
    private array $orderBy;
    private array $limitAndOffet;
    private ?string $custom = null;

    private array $aggregate;
    private array $aggField;
    private array $recursiveQuery;
    private ?CollectionInterface $queryParams = null;
    private array $params = [];
    private array $bindAry = [];

    public function __construct(?CollectionInterface $queryParams)
    {
        $this->selectors = $queryParams->all()['tables']['selectors'];
        $this->joinRules = $queryParams->all()['joinRules']['tables'];
        $this->mainTable = $queryParams->all()['tables']['mainTable'];
        $this->groupBy = $queryParams->all()['groupBy'];
        $this->orderBy = $queryParams->all()['orderBy'];
        $this->limitAndOffet = $queryParams->all()['limitAndOffet'];
        $this->custom = $queryParams->all()['customQuery'];
        $this->queryParams = $queryParams;
    }

    public function query() : string
    {
        list($query, $queryRecursive) = $this->mainQuery();
        if (isset($this->recursiveQuery)) {
            $query = $this->recursive($queryRecursive, $query);
        }
        return $query;
        // . (isset($this->bindAry) ? '&' . serialize($this->bindAry) : '');

        // if (! isset($this->custom)) {
        //     $query = 'SELECT ';
        //     $query .= $this->selectors() . $this->from() . $this->join() . $this->where() . $this->groupBy() . $this->having() . $this->orderBy() . $this->limitOffset();
        //     return $query;
        // } else {
        //     return $this->custom;
        // }
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

    private function mainQuery() : array
    {
        list($query, $recursivequery) = match (true) {
            isset($this->joinRules) => $this->recursiveQuery(
                $this->baseQuery() . $this->join()
            ),
            default => $this->recursiveQuery($this->baseQuery()),
        };
        $query .= $this->where() . $this->groupBy() . $this->having() . $this->orderBy() . $this->limitOffset();
        return [$query, $recursivequery];
    }

    private function baseQuery() : string
    {
        if (! isset($this->custom)) {
            if (isset($this->aggregate)) {
                return "SELECT {$this->aggregate}({$this->aggField}) " . $this->from();
            } else {
                return 'SELECT ' . $this->selectors() . ' ' . $this->from();
            }
        } else {
            return $this->custom;
        }
    }

    private function recursiveQuery(string $query) : array
    {
        if (isset($this->recursiveQuery)) {
            return [$query, $this->recursiveQuery];
        }
        $q = $query;
        $sql = 'SELECT ';
        if (isset($this->recursiveQuery) && array_key_exists('recursive', $this->recursiveQuery['options'])) {
            $recursive = $this->recursiveQuery['options'];
            if (array_key_exists('COUNT', $recursive) && count($this->selectors) === 1) {
                $sql .= 'p.' . $recursive['field'] . ' FROM ' . $this->mainTable['name'] . ' p ';
                $sql .= 'INNER JOIN cte ON p.' . $recursive['parentID'] . '= cte.' . $recursive['id'] . ')';
                $sql .= 'SELECT COUNT(' . $recursive['field'] . ') AS ' . $recursive['AS'] . ' FROM cte;';
                return [$q, $sql];
            }
        }
        return [$q, ''];
    }

    private function recursive(string $query, string $globalQuery) : string
    {
        $sql = 'WITH RECURSIVE cte AS (';
        $sql .= '(' . $globalQuery . ') ';
        $sql .= 'UNION ALL ';
        $sql .= $query;
        return $sql;
    }

    private function selectors() : string
    {
        try {
            if (! empty($this->selectors)) {
                if (null == $this->joinRules) {
                    return implode(', ', $this->selectors);
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
            foreach ($this->selectors as $selector) {
                $separator = $selector === end($this->selectors) ? ' ' : ', ';
                $selectors .= $this->selectorAndTable($selector) . $separator;
            }
            // foreach ($this->joinRules as $rule) {
            //     if (empty($rule['selectors'])) {
            //         $separator = $rule === end($this->joinRules) ? '' : ' ,';
            //         $selectors .= $rule['table'] . '.*' . $separator;
            //     } else {
            //         foreach ($rule['selectors'] as $selector) {
            //             $separator = $selector === end($this->selectors) ? '' : ' ,';
            //             $selectors .= $rule['table'] . '.' . $selector . $separator;
            //         }
            //     }
            // }
            return $selectors;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function selectorAndTable(string $selector) : string
    {
        $parts = explode('|', $selector);
        if (count($parts) > 1) {
            return $parts[0] . '.' . $parts[1];
        }
        return $selector;
    }

    private function from() : string
    {
        try {
            if (isset($this->mainTable)) {
                return 'FROM ' . $this->table($this->mainTable) . ' ';
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

    private function join() : string
    {
        try {
            $join = '';
            if (count($this->joinRules) > 0) {
                foreach ($this->joinRules as $JoinRule) {
                    $sep = end($this->joinRules) == $JoinRule ? '' : '';
                    $join .= ' ' . strtoupper($JoinRule['rule']) . ' ' . $this->table($JoinRule['table']) . ' ';
                    $join .= 'ON ' . $this->on($JoinRule) . $sep;
                }
            }
            return $join;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function on(array $JoinRules = []) : string
    {
        try {
            if (isset($JoinRules) && ! empty($JoinRules) >= 1) {
                $on = '';
                $onConditions = $this->getOnConditions($JoinRules);
                if ($onConditions) {
                    list($condition, $params, $bind) = $onConditions->proceed();
                    $on .= $condition;
                    $bind = ArrayUtil::flatten_with_keys($bind);
                    $params = ArrayUtil::flatten_with_keys($params);
                    ! empty($params) ? $this->params[__FUNCTION__][] = $params : '';
                    ! empty($bind) ? $this->bindAry[__FUNCTION__][] = $bind : '';
                }
                return $on;
            } else {
                throw new BadQueryArgumentException('Can not join tables');
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function getOnConditions(array $joinRules) : Conditions|bool
    {
        $table = $joinRules['table'];
        $onconditions = $this->queryParams->all()['joinRules']['on'];
        foreach ($onconditions as $condition) {
            if ($condition->getTbl() == $table) {
                return $condition;
            }
        }
        return false;
    }

    private function conditions(array $aryCond) : string
    {
        try {
            $condition = $aryCond['braceOpen'];
            if ($aryCond['type'] == 'expression') {
                $condition .= key($aryCond['rule']) . $aryCond['operator'] . $aryCond['rule'][key($aryCond['rule'])] . $aryCond['braceClose'];
            } elseif ($aryCond['type'] == 'value') {
                $condition .= key($aryCond['rule']) . $aryCond['operator'] . ":{$this->explodedField(key($aryCond['rule']))}" . $aryCond['braceClose'];
            }
            return ' ' . $condition . ' ' . $aryCond['link'];
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function explodedField(string $field) : string
    {
        $parts = explode('.', $field);
        if (count($parts) > 1) {
            return $parts[1];
        }
        return $field;
    }

    private function where() : string
    {
        $conditionsCollection = $this->queryParams->all()['conditions'];
        try {
            $where = '';
            if ($conditionsCollection->getChildrenStorage()->count() > 0) {
                $where .= ' WHERE ';
                list($condition, $params, $bind) = $conditionsCollection->proceed();
                $bind = ArrayUtil::flatten_with_keys($bind);
                $params = ArrayUtil::flatten_with_keys($params);
                ! empty($params) ? $this->params[__FUNCTION__][] = $params : '';
                ! empty($bind) ? $this->bindAry[__FUNCTION__][] = $bind : '';
                $where .= $condition;
            }
            return $where;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function whereConditions(array $aryCond) : string
    {
        try {
            switch (true) {
                case in_array($aryCond['operator'], ['NOT IN', 'IN']):
                    $prefixer = $this->arrayPrefixer(key($aryCond['rule']), $aryCond['rule'][key($aryCond['rule'])], $this->bindAry);
                    return $aryCond['braceOpen'] . key($aryCond['rule']) . $aryCond['operator'] . ' (' . $prefixer . ')' . $aryCond['braceClose'] . $aryCond['link'];
                    break;
                case in_array($aryCond['operator'], ['IS NULL', 'NOT NULL']):
                    break;
                case $aryCond['operator'] === 'LIKE':
                    break;
                default:
                    return $this->conditions($aryCond); //$braceOpen . $tbl1 . $field . $operator . ":$field" . $braceEnd . $link;
                    break;
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function arrayPrefixer(string $prefix, array $values, array &$bindArray) : string
    {
        $str = '';
        foreach ($values as $index => $value) {
            $str .= ':' . $prefix . $index . ',';
            $bindArray[$prefix . $index] = $value;
        }
        return rtrim($str, ',');
    }

    private function groupBy() : string
    {
        try {
            $groupBy = '';
            if (is_array($this->groupBy) && ! empty($this->groupBy)) {
                $groupBy .= ' GROUP BY ' . implode(', ', $this->groupBy);
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
            /** @var Conditions */
            $having = $this->queryParams->all()['havingConditions'];
            if ($having->getChildrenStorage()->count() > 0) {
                $havingCond .= ' HAVING ';
                list($condition, $params, $bind) = $having->proceed();
                $havingCond .= $condition;
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
            if (is_array($this->orderBy) && ! empty($this->orderBy)) {
                $orderBy .= ' ORDER BY ';
                foreach ($this->orderBy as $order) {
                    $sep = end($this->orderBy) == $order ? '' : ', ';
                    $orderBy .= $order . $sep;
                }
            }
            return $orderBy;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function limitOffset() : string
    {
        try {
            $limitOffset = '';
            if (is_array($this->limitAndOffet) && ! empty($this->limitAndOffet)) {
                if (isset($this->limitAndOffet['limit']) && isset($this->limitAndOffet['offset']) && $this->limitAndOffet['offset'] != -1) {
                    $limitOffset .= ' LIMIT :offset, :limit';
                }
                if (isset($this->limitAndOffet['limit']) && ! isset($this->limitAndOffet['offset'])) {
                    $limitOffset .= ' LIMIT :limit';
                }
            }
            return $limitOffset;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }
}