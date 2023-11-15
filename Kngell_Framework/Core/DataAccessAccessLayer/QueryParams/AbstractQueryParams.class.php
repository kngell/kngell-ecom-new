<?php

declare(strict_types=1);
abstract class AbstractQueryParams implements QueryParamsInterface
{
    protected const SEPARATOR = ['OR', 'XOR'];
    protected const OPERATOR = ['!=', '=', '<=', '>=', '<>', 'IN', 'NOT IN'];
    protected string $current_table = '';
    protected string $tableSchema;
    protected null|Entity|CollectionInterface $entity;
    protected string $tableSuffix = '';
    /** @var array */
    protected array $query_params = [];
    /** @var array */
    protected array $conditionBreak = [];
    private string $braceOpen = '';

    public function __construct(string $tableSchema, ?object $entity)
    {
        $this->tableSchema = $tableSchema;
        $this->entity = $entity;
    }

    public function hasConditions() : bool
    {
        return (isset($this->query_params['conditions']) && $this->query_params['conditions'] != []) ? true : false;
    }

    public function reset() : self
    {
        $this->query_params = [];
        $this->conditionBreak = [];
        $this->braceOpen = '';
        return $this;
    }

    protected function separator(mixed $separator, mixed $key) : string
    {
        if (is_string($separator) && is_numeric($key) && in_array(strtoupper($separator), self::SEPARATOR)) {
            return strtoupper($separator);
        }
        return 'AND';
    }

    protected function fieldOperator(string $field) : array
    {
        if (str_contains($field, '|')) {
            $parts = explode('|', $field);
            $operator = '';
            foreach ($parts as $k => $part) {
                if (in_array(strtoupper($part), self::OPERATOR)) {
                    $operator = strtoupper($parts[$k]);
                    unset($parts[$k]);
                }
            }
            if (! count($parts) === 1) {
                throw new BaseInvalidArgumentException('Argument ou condition mal renseignée');
            }
            return [current($parts), $operator];
        }
        return [$field, '='];
    }

    protected function fieldValue(mixed $value, string $operator, ?string $whereType = null) : array
    {
        if ($whereType != null) {
            return [$value, ''];
        }
        $tbl = $this->tableSchema;
        if (is_array($value) && ! empty($value)) {
            if (array_key_exists('tbl', $value)) {
                $tbl = $value['tbl'];
                unset($value['tbl']);
            } elseif (count($value) == count($value, COUNT_RECURSIVE)) {
                $tbl = $this->tableSchema;
            } else {
                $tbl = $value[1];
                $value = $value[0];
                unset($value[1]);
            }
            // foreach ($value as $key => $val) {
            //     return [$val, $tbl];
            // }
            // if (count($value) > 2) {
            // $val = [];
            // foreach ($value as $v) {
            //     if ($v != '') {
            //         $val[] = $v;
            //     }
            // }
            return [$value, $tbl];
            // }
        }
        if (is_string($value) && str_contains($value, '|')) {
            $parts = explode('|', $value);
            if ($parts[0] && $parts[0] === 'collection' && $this->entity->count() > 0) {
                $val = [];
                foreach ($this->entity as $entity) {
                    $getter = $entity->getGetters($parts[1]);
                    $val[] = $entity->$getter();
                }
                $tbl = isset($parts[2]) ? $parts[2] : $this->tableSchema;
                return [$val, $tbl];
            } else {
                return [$parts[0], $parts[1]];
            }
        }
        return [$value, $tbl];
    }

    protected function condition(array $params) : array
    {
        $where = [];
        $this->key('conditions');
        if (is_string($params['field'])) {
            $where[$params['field']] = ! is_array($params['value']) ? ['value' => $params['value'], 'tbl' => ! isset($params['tbl']) ? $this->tableSchema : $params['tbl']] : ['value' => $params['value'], 'tbl' => ! isset($params['tbl']) ? $this->tableSchema : $params['tbl']];
        }
        if ($params['operator'] != '') {
            $where[$params['field']]['operator'] = $params['operator'];
        }
        if ($params['separator'] != '') {
            $where[$params['field']]['separator'] = $params['separator'];
        }
        if ($params['braceOpen'] != '') {
            $where[$params['field']]['braceOpen'] = $params['braceOpen'];
        }
        if ($params['braceEnd'] != '') {
            $where[$params['field']]['braceEnd'] = $params['braceEnd'];
        }
        return $where;
    }

    protected function getField(string $field) : array
    {
        if (str_contains($field, '|')) {
            $parts = explode('|', $field);
            return count($parts) > 1 ? [$parts[0], $parts[1]] : [$parts[0], ''];
        }
        return [];
    }

    protected function addTableToOptions(?string $tbl = null, mixed $columns = null) : void
    {
        $tbl == null ? $tbl = $this->tableSchema : '';
        $this->key('options');
        if (! array_key_exists('table', $this->query_params['options'])) {
            $this->query_params['options']['table'] = [];
        }
        $this->query_params['options']['table'][] = $tbl;
        $this->current_table = $tbl;
    }

    protected function key(string $key) : void
    {
        if (! array_key_exists($key, $this->query_params)) {
            $this->query_params[$key] = [];
        }
    }

    protected function braceOpen(array $conditions) : string
    {
        $prevCondition = isset($this->query_params['conditions']) ? current($this->query_params['conditions']) : [];
        if (count($conditions) > 2 || (isset($prevCondition['separator']) && in_array($prevCondition['separator'], self::SEPARATOR))) {
            return $this->braceOpen = '(';
        }
        return '';
    }

    protected function braceClose(string $separator, mixed $key) : string
    {
        if (! empty($this->braceOpen)) {
            $this->braceOpen = '';
            return ')';
        }
        return '';
    }

    protected function whereParams(array $conditions, mixed $key, mixed $value) : array
    {
        $whereParams = [];
        $lastKey = array_key_last($conditions);
        $firstKey = key($conditions);
        $whereParams['separator'] = ($key != $lastKey) ? $this->separator(next($conditions), key($conditions)) : '';
        $whereParams['braceOpen'] = ($key == $firstKey) || (is_numeric($key) && in_array($value, ['or', 'and']) || ! empty($this->conditionBreak)) ? $this->braceOpen($conditions) : '';
        $whereParams['braceEnd'] = $this->braceClose($whereParams['separator'], $key);
        $whereParams['value'] = $value;
        return $whereParams;
    }

    protected function getJoinOptions(string|int $key, string $arg, int $position, int $index) : void
    {
        $tbl = '';
        $field = '';
        if (null != $arg && str_contains($arg, '|')) {
            $parts = is_string($arg) ? explode('|', $arg) : '';
            $tbl = $parts[1];
            $field = $parts[0];
        } elseif (is_numeric($key)) {
            $tbl = $this->query_params['options']['table'][$index];
            $field = $arg;
        }
        if ($tbl != '' & $field != '') {
            $this->query_params['options']['join_on'][$position][$key] = $tbl . '.' . $field;
        }
    }

    protected function getAryParams(string $k, array $arg)
    {
        $tbl = $arg[1];
    }

    protected function getParams(string $k, mixed $arg) : void
    {
        $parts = is_string($k) ? explode('|', $k) : '';
        if (empty(end($parts))) {
            unset($parts[count($parts) - 1]);
        }
        $field = $parts[0] == 'or' ? $parts[1] : $parts[0];
        if (! array_key_exists('params', $this->query_params['options']['join_on'])) {
            $this->query_params['options']['join_on']['params'] = [];
        }
        $tbl = is_array($arg) ? $arg[1] : $this->current_table;
        $value = is_array($arg) ? $arg[0] : $arg;
        array_push($this->query_params['options']['join_on']['params'], [
            $tbl . '.' . $field, $value,
            'separator' => $parts[0] == 'or' ? 'OR' : 'AND',
            'operator' => $this->operator($parts),
        ]);
    }

    protected function parseTable(?string $tbl = null) : ?string
    {
        if (null != $tbl && str_contains($tbl, '|')) {
            $parts = explode('|', $tbl);
            $this->tableSuffix = trim($parts[1]);
            return trim($parts[0]);
        }
        return $tbl;
    }

    protected function getSelectors() : array
    {
        $selectors = [];
        $tbl_columns = $this->tableColumns();
        $this->key('selectors');
        foreach ($tbl_columns as $tbl => $columns) {
            if (! is_array($columns)) {
                throw new Exception('Columns must be in array!');
            }
            foreach ($columns as $column) {
                if (str_contains($column, '|')) {
                    $parts = explode('|', $column);
                    if (is_array($parts) && count($parts) < 3) {
                        array_push($selectors, $parts[0] . '(' . $tbl . '.' . $parts[1] . ')');
                    } else {
                        array_push($selectors, $parts[0] . '(' . $tbl . '.' . $parts[1] . ') AS ' . $parts[2]);
                    }
                } else {
                    array_push($selectors, $tbl . '.' . $column);
                }
            }
        }
        return $this->query_params['selectors'] = $selectors;
    }

    protected function tableColumns() : array
    {
        if ($this->entity !== null && $this->entity instanceof CollectionInterface && $this->entity->count() > 0) {
            $entity = $this->entity->all()[0];
            $attrs = $entity->getInitializedAttributes();
            $tbl = StringUtil::separate(substr($entity::class, 0, strpos($entity::class, 'Entity')));
            return [$tbl => array_keys($attrs)];
        }
        return array_key_exists('table_join', $this->query_params) ? $this->query_params['table_join'] : [];
    }

    protected function recursiveCount() : void
    {
        if (isset($this->query_params['table_join'])) {
            foreach ($this->query_params['table_join'] as $tbl => $tblParams) {
                foreach ($tblParams as $field) {
                    if (str_contains($field, 'COUNT')) {
                        $part = explode('|', $field);
                        $this->query_params['options']['recursive']['COUNT'] = $part[0] ?? '';
                        $this->query_params['options']['recursive']['field'] = $part[1] ?? '';
                        $this->query_params['options']['recursive']['AS'] = $part[2] ?? '';
                    }
                }
            }
        }
    }

    private function operator(array $parts) : string
    {
        $end = end($parts);
        if (in_array($end, self::OPERATOR)) {
            return $end;
        } else {
            return '=';
        }
    }
}