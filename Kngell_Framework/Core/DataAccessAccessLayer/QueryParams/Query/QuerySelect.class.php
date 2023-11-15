<?php

declare(strict_types=1);

class QuerySelect implements QueryInterface
{
    private string $mainTable;
    private array $selectors;
    private array $joinRules;
    private array $conditions;
    private array $groupBy;
    private array $having;
    private array $orderBy;
    private array $limitAndOffet;
    private ?string $custom = null;
    private array $bindAry = [];

    public function __construct(?CollectionInterface $queryParams = null)
    {
        $this->selectors = $queryParams->all()['mainTable']['selectors'];
        $this->mainTable = $queryParams->all()['mainTable']['name'];
        $this->joinRules = $queryParams->all()['joinRules'];
        $this->conditions = $queryParams->all()['conditions'];
        $this->groupBy = $queryParams->all()['groupBy'];
        $this->having = $queryParams->all()['havingConditions'];
        $this->orderBy = $queryParams->all()['orderBy'];
        $this->limitAndOffet = $queryParams->all()['limitAndOffet'];
        $this->custom = $queryParams->all()['customQuery'];
    }

    public function query() : string
    {
        if (! isset($this->custom)) {
            $query = 'SELECT ';
            $query .= $this->selectors() . $this->from() . $this->join() . $this->where() . $this->groupBy() . $this->having() . $this->orderBy() . $this->limitOffset();
            return $query;
        } else {
            return $this->custom;
        }
    }

    private function selectors() : string
    {
        try {
            if (! empty($this->selectors)) {
                if (null == $this->joinRules) {
                    return implode(' ,', $this->selectors);
                } else {
                    return $this->selectorsWithJoinRules();
                }
            }
            return ' *';
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function selectorsWithJoinRules() : string
    {
        try {
            $selectors = '';
            foreach ($this->selectors as $mainSelector) {
                $selectors .= $this->mainTable . '.' . $mainSelector . ', ';
            }
            foreach ($this->joinRules as $rule) {
                if (empty($rule['selectors'])) {
                    $separator = $rule === end($this->joinRules) ? '' : ' ,';
                    $selectors .= $rule['table'] . '.*' . $separator;
                } else {
                    foreach ($rule['selectors'] as $selector) {
                        $separator = $selector === end($this->selectors) ? '' : ' ,';
                        $selectors .= $rule['table'] . '.' . $selector . $separator;
                    }
                }
            }
            return $selectors;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function from() : string
    {
        try {
            if (isset($this->mainTable)) {
                return ' FROM ' . $this->mainTable . ' ';
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function join() : string
    {
        try {
            $join = '';
            $braceOpen = '';
            $braceClose = '';
            if ((null !== $this->joinRules)) {
                if (count($this->joinRules) > 1) {
                    $braceOpen = '(';
                    $braceClose = ')';
                }
                foreach ($this->joinRules as $rules) {
                    $join .= ' ' . strtoupper($rules['relation'] . 'join') . ' ' . $rules['table'] . ' ';
                    $join .= 'ON ' . $braceOpen . $this->on($rules) . $braceClose;
                }
            }
            return $join;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function on(array $rules = []) : string
    {
        try {
            if (isset($rules['on']) && is_array($rules['on']) && count($rules['on']) >= 1) {
                $on = '';
                foreach ($rules['on'] as $key => $onRule) {
                    $on .= $this->conditions($onRule);
                }
                // $on .= $braceClose;
                return $on;
            } else {
                throw new BadQueryArgumentException('Can not join tables');
            }
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function conditions(array $aryCond) : string
    {
        try {
            $condition = $aryCond['braceOpen'];
            $link = isset($aryCond['link']) ? ' ' . $aryCond['link'] . ' ' : '';
            $field = key($aryCond['rule']);
            if (is_array($aryCond) && ! empty($aryCond)) {
                $tbl1 = ! empty($aryCond['tbl1']) ? $aryCond['tbl1'] . '.' : '';
                $tbl2 = ! empty($aryCond['tbl2']) ? $aryCond['tbl2'] . '.' : '';
                if ($aryCond['type'] == 'expression') {
                    $condition .= $tbl1 . key($aryCond['rule']) . $aryCond['operator'] . $tbl2 . $aryCond['rule'][key($aryCond['rule'])] . $aryCond['braceClose'];
                } elseif ($aryCond['type'] == 'value') {
                    $condition .= $tbl1 . $field . $aryCond['operator'] . ":$field" . $aryCond['braceClose'];
                }
                //$braceOpen . $tbl1 . $field . $operator . ":$field" . $braceEnd . $link;
            }
            return $condition . $link;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function where() : string
    {
        try {
            $where = '';
            if (is_array($this->conditions) && ! empty($this->conditions)) {
                $where .= ' WHERE ';
                foreach ($this->conditions as $condition) {
                    $where .= $this->whereConditions($condition);
                }
            }
            return $where;
        } catch (\Throwable $th) {
            throw new BadQueryArgumentException($th->getMessage(), $th->getCode());
        }
    }

    private function whereConditions(array $aryCond) : string
    {
        try {
            $link = isset($aryCond['link']) ? ' ' . $aryCond['link'] . ' ' : '';
            $operator = isset($aryCond['operator']) ? ' ' . $aryCond['operator'] . ' ' : '';
            $braceOpen = isset($aryCond['braceOpen']) ? ' ' . $aryCond['braceOpen'] . ' ' : '';
            $braceEnd = isset($aryCond['braceEnd']) ? ' ' . $aryCond['braceEnd'] . ' ' : '';
            $tbl1 = ! empty($aryCond['tbl1']) ? $aryCond['tbl1'] . '.' : '';
            $field = key($aryCond['rule']);
            switch (true) {
                case in_array($operator, ['NOT IN', 'IN']):
                    $prefixer = $this->arrayPrefixer($field, $aryCond['rule'][$field], $this->bindAry);
                    return $braceOpen . $tbl1 . $field . $operator . ' (' . $prefixer . ')' . $braceEnd . $link;
                    break;
                case in_array($operator, ['IS NULL', 'NOT NULL']):
                    break;
                case $operator === 'LIKE':
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
                $groupBy .= ' GROUP BY ';
                foreach ($this->groupBy as $group) {
                    foreach ($group['fields'] as $field) {
                        $sep = $field == end($this->groupBy) ? '' : ', ';
                        $groupBy .= $group['tbl'] . '.' . $field . $sep;
                    }
                }
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
            if (is_array($this->having) && ! empty($this->having)) {
                $havingCond .= ' HAVING ';
                foreach ($this->having as $having) {
                    $havingCond .= $this->whereConditions($having);
                }
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
                    $orderBy .= key($order) . ' ' . $order[key($order)] . $sep;
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