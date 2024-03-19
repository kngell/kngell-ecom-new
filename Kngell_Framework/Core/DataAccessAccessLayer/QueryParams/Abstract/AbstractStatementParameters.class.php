<?php

declare(strict_types=1);

abstract class AbstractStatementParameters extends AbstractQueryStatement
{
    protected array $params = [];
    protected mixed $field;
    protected array $fields;
    protected mixed $values;
    protected string $cteAlias;
    protected string $cte;
    protected Entity|CollectionInterface $entity;

    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        $this->params = $params;
        isset($this->params['entity']) ? $this->entity = $this->params['entity'] : '';
        parent::__construct($method, $baseMethod, $queryType);
    }

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get the value of bind_arr.
     */
    public function getBindArr(): array
    {
        return $this->bind_arr;
    }

    /**
     * Set the value of bind_arr.
     */
    public function setBindArr(array $bind_arr): self
    {
        $this->bind_arr = $bind_arr;

        return $this;
    }

    /**
     * Get the value of parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Set the value of parameters.
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

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

    public function arrayPrefixer(array $values, mixed $key = null) : string
    {
        $str = '';
        if (ArrayUtil::isAssoc($values)) {
            $keys = array_keys($values);
            $values = ArrayUtil::valuesFromArray($values);
        }
        foreach ($values as $index => $value) {
            isset($keys) ? $this->arrayPrefixerField($keys, $index, $key) : '';
            $str .= ':' . $this->field . $index . ',';
            $this->bind_arr[$this->field][$this->field . $index] = $value;
        }
        return rtrim($str, ',');
    }

    /**
     * Get the value of fields.
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields.
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    protected function table() : string|bool
    {
        if (isset($this->entity)) {
            $entity = $this->entity instanceof CollectionInterface ? $this->entity->first() : $this->entity;
            return $entity->table();
        }
        $parts = explode('|', $this->tbl);
        if (count($parts) == 2) {
            return $parts[0];
        }
        if (count($parts) == 1) {
            return $this->tbl;
        }
        return false;
    }

    protected function braceOpen() : string
    {
        if (str_ends_with($this->method, 'In') || $this->method === 'set') {
            return '';
        }
        return  $this->braceOpen . '( ';
    }

    protected function braceClose() : string
    {
        if (str_ends_with($this->method, 'In') || $this->method === 'set') {
            return '';
        }
        return  $this->braceClose . ' )';
    }

    protected function closureCheck() : array
    {
        $condition = isset($this->params['data']) ? $this->params['data'] : [];
        if (isset($condition[0]) && $condition[0] instanceof Closure) {
            $queryParams = $this->params['queryParams'];
            call_user_func($condition[0], $queryParams);
            return $queryParams->build()->getQuery()->setTableAlias($this->tableAlias)->setAliasCheck($this->aliasCheck)->proceed();
        }
        return ['', [], []];
    }

    protected function tableAlias(string $tbl) : string
    {
        $alias = $this->getTblAlias($tbl);
        if (! $alias) {
            $alias = '';
            $t = '';
            if ($tbl !== null) {
                $parts = explode('|', $tbl);
                if (count($parts) == 1) {
                    $alias = strtolower($tbl[0]);
                    $t = $tbl;
                } elseif (count($parts) == 2) {
                    $alias = $parts[1];
                    $t = trim($parts[0]);
                }
                while (in_array($alias, $this->aliasCheck) || is_numeric($alias)) {
                    $alias = $this->token->generate(1, $tbl);
                }
                array_push($this->aliasCheck, $alias);
                $this->tableAlias[$t] = $alias;
            }
            $this->tbl = $t;
        }
        return $alias;
    }

    protected function getTblAlias(string $table) : string|bool
    {
        foreach ($this->tableAlias as $tbl => $alias) {
            if ($table == $tbl) {
                return $alias;
            }
        }
        return false;
    }

    protected function parseFields(array $conditions = [], ?string $method = null) : array
    {
        $cond = [];
        $type = $this->type($conditions);

        if ($method !== null && str_ends_with($method, 'In')) {
            $inArgs = $conditions;
            $conditions = [$inArgs['field']];
        }

        foreach ($conditions as $key => $condition) {
            if (Operators::exists($condition)) {
                $cond[] = $condition;
                continue;
            }
            if (is_array($condition)) {
                $cond[] = $this->parseFields($condition, $method);
            } else {
                if (is_string($condition)) {
                    $parts = explode('|', $condition);
                    if (count($parts) == 2) {
                        $alias = $this->alias($parts[0]);
                        $alias = (! $alias ? $parts[0] : $alias) . (count($parts) > 1 ? '.' : '');
                        $field = strval($parts[1]);
                        $key == 0 ? $this->field = $field : '';
                    } elseif (count($parts) == 1) {
                        $alias = '';
                        $field = is_string($condition) ? strval($condition) : $condition;
                        $key == 0 ? $this->field = $field : '';
                    }
                    $cond[] = $type == 'exp' ? $alias . $field : $field;
                } else {
                    $cond[$key] = $condition;
                }
            }
        }
        if ($method !== null && str_ends_with($method, 'In')) {
            $cond['field'] = $cond[0];
            $cond['list'] = $inArgs['list'];
            unset($cond[0]);
        }
        return [$type, $cond];
    }

    protected function alias(string $tbl) : string|bool
    {
        $alias = $this->getTblAlias($tbl);
        if (! $alias && ! in_array($tbl, $this->aliasCheck)) {
            throw new BadQueryArgumentException("Table $tbl does not exist or is not define");
        }
        return $alias;
    }

    protected function operator(array $condition) : string
    {
        if (! str_ends_with($this->method, 'In') && count($condition) === 3) {
            return ' ' . Operators::getFromValue($condition[1]) . ' ';
        }
        return ' ' . Operators::getOp($this->method, $this->baseMethod) . ' ';
    }

    private function arrayPrefixerField(array $keys, mixed $index, mixed $key = null) : void
    {
        if (null !== $key) {
            $this->field = $keys[$index] . $key;
        } else {
            $this->field = $keys[$index];
        }
    }

    private function type(array $condition) : string
    {
        return match (true) {
            in_array($this->queryType, ['INSERT', 'UPDATE', 'UPDATECTE']) => 'value' ,
            isset($condition['list']) => 'value',
            default => $this->defaultType($condition),
        };
    }

    private function defaultType(array $condition) : string
    {
        $value = count($condition) == 2 ? $condition[1] : $condition[2];
        if (is_numeric($value)) {
            return 'value';
        }
        $parts = explode('|', $value);
        if (count($parts) > 1) {
            if ($parts[array_key_last($parts)] === 'value') {
                return 'value';
            }
            return 'exp';
        } else {
            return match (true) {
                in_array($this->baseMethod, ['on', 'join']) => 'exp',
                $this->baseMethod == 'where' => 'value',
            };
        }
    }
}