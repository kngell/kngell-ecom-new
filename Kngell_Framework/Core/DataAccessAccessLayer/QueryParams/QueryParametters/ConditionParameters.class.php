<?php

declare(strict_types=1);

class ConditionParameters extends AbstractQueryStatement
{
    private array $params;
    private string $field;
    private array $bindArray;
    private array $fieldParameters;

    public function __construct(array $params = [], ?string $method = null, ?CollectionInterface $children = null, ?QueryParamsHelper $helper = null)
    {
        parent::__construct($children, $helper, $method);
        $this->params = $params;
    }

    public function proceed(): array
    {
        $cdt = isset($this->params['condition']) ? $this->params['condition'] : [];
        $condition = $this->condition($cdt);
        return [$condition, $this->fieldParameters ?? [], $this->bindArray ?? []];
    }

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Get the value of fieldParameters.
     */
    public function getFieldParameters(): array
    {
        return $this->fieldParameters;
    }

    /**
     * Set the value of fieldParameters.
     */
    public function setFieldParameters(array $fieldParameters): self
    {
        $this->fieldParameters = $fieldParameters;

        return $this;
    }

    private function condition(array $condition) : string
    {
        return $this->braceOpen() . $this->rule($condition) . $this->braceClose();
        // return [
        //     'operator' => $this->operator($condition),
        //     'tbl1' => $this->tbl($condition),
        //     'tbl2' => $this->tbl($condition, 2),
        //     'type' => $this->type($condition),
        //     'link' => $this->link($key),
        //     'braceOpen' => $this->braceOpen(),
        //     'braceClose' => $this->braceClose(),
        //     'rule' => $this->rule($condition, $key),
        // ];
    }

    private function operator(array $condition) : string
    {
        if (str_ends_with($this->method, 'NotIn')) {
            return ' NOT IN ';
        }
        if ($this->method == 'whereIn') {
            return ' IN ';
        }
        if (count($condition) === 3) {
            return ' ' . $condition[1] . ' ';
        }
        return ' = ';
    }

    private function tbl(array $condition, int $tbl = 1) : string
    {
        if ($tbl == 1) {
            $field = $condition[0];
        } elseif ($tbl == 2) {
            $field = count($condition) === 2 ? $condition[1] : $condition[2];
        }
        if (! is_numeric($field)) {
            $parts = explode('.', $field);
            if (count($parts) > 1) {
                return $this->type($condition) == 'expression' ? '' : $parts[0] . '.';
            }
        }
        return '';
    }

    private function type(array $condition) : string
    {
        $value = count($condition) == 2 ? $condition[1] : $condition[2];
        if (is_numeric($value)) {
            return 'value';
        } else {
            $parts = explode('|', $value);
            if (count($parts) > 1) {
                return end($parts) === 'exp' ? 'expression' : 'value';
            }
            return 'value';
        }
    }

    private function field(array $condition) : mixed
    {
        $field = $condition['0'];
        $parts = explode('.', $field);
        if (count($parts) == 1) {
            return $this->field = $parts[0];
        }
        if (count($parts) > 1) {
            return $this->field = $parts[1];
        }
    }

    private function value(array $condition) : mixed
    {
        $value = count($condition) === 2 ? $condition[1] : $condition[2];
        if (in_array($this->method, ['whereNotIn', 'havingNotIn', 'whereIn'])) {
            unset($condition[0]);
            $value = array_values($condition);
        }
        if (! is_numeric($value) && ! is_array($value)) {
            $parts = explode('|', $value);
            if (count($parts) == 1) {
                $value = $parts[0];
            } elseif (count($parts) > 1) {
                $value = $this->type($condition) == 'expression' ? $parts[0] : $parts[1];
            }
        } elseif (is_array($value)) {
            $value = '(' . $this->arrayPrefixer($value) . ')';
            // . '|&' . serialize($bindArray) . '|&'
        }
        return $value;
    }

    private function arrayPrefixer(array $values) : string
    {
        $str = '';
        $this->bindArray = [];
        foreach ($values as $index => $value) {
            $str .= ':' . $this->field . $index . ',';
            $this->bindArray[$this->field][$this->field . $index] = $value;
        }
        return rtrim($str, ',');
    }

    private function rule(array $condition) : string
    {
        $tbl1 = $this->tbl($condition);
        $tbl2 = $this->tbl($condition, 2);
        $type = $this->type($condition);
        $field = $this->field($condition);
        $value = $this->value($condition);
        if ($type == 'expression') {
            $parts = explode('|', $value);
            if (count($parts) == 1) {
                if (! str_starts_with($value, 'COUNT')) {
                    $value = $parts[0];
                }
            } elseif (count($parts) > 1) {
                $value = $parts[1];
            }
        }
        if (isset($this->bindArray)) {
            $stmt = $value;
        } else {
            if ($this->method !== 'on') {
                $this->fieldParameters[$field] = $value;
                $stmt = ':' . $field;
            } else {
                $stmt = $value;
            }
            // $stmt = $this->method !== 'on' ? ':' . $field : $this->tblAlias($condition, 2) . $value;
        }

        return $tbl1 . $field . $this->operator($condition) . $stmt;
    }

    private function tblAlias(array $condition) : string
    {
        return $this->tbl($condition, 2);
    }

    private function braceOpen() : string
    {
        return $this->braceOpen . '( ';
    }

    private function braceClose() : string
    {
        return $this->braceClose . ' )';
    }
}