<?php

declare(strict_types=1);
abstract class AbstractConditionsProcessing
{
    protected QueryConditionsHelper $helper;
    protected array $conditions = [];
    protected int $level;
    protected string $method;
    protected string $tbl;
    protected bool $closureState;

    public function __construct(protected CollectionInterface $conditionsList)
    {
    }

    abstract public function proceed() : array;

    public function addConditions(array $conditions) : self
    {
        $this->conditions = $conditions;
        return $this;
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
     * Set the value of tbl.
     */
    public function setTbl(string $tbl): self
    {
        $this->tbl = $tbl;
        return $this;
    }

    /**
     * Set the value of helper.
     */
    public function setHelper(QueryConditionsHelper $helper): self
    {
        $this->helper = $helper;
        return $this;
    }

    /**
     * Set the value of level.
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Set the value of closureState.
     */
    public function addClosureState(bool $closureState): self
    {
        $this->closureState = $closureState;
        return $this;
    }

    /**
     * Set the value of conditions.
     */
    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    protected function condition(array $condition, int $key, bool $closure = false) : array
    {
        if (! in_array(count($condition), [2, 3])) {
            throw new BadQueryArgumentException('Cannot handle this where clause');
        }
        return [
            'operator' => $this->operator($condition),
            'tbl1' => $this->tbl($condition),
            'tbl2' => $this->tbl($condition, 2),
            'type' => $this->type($condition),
            'link' => $this->link($key),
            'braceOpen' => $this->braceOpen($key, $closure),
            'braceClose' => $this->braceClose($key, $closure),
            'rule' => $this->rule($condition, $key),
        ];
    }

    private function rule(array $condition, int $key) : array
    {
        $parts = explode('|', $condition['0']);
        if (count($parts) == 1) {
            $tbl1 = $key == array_key_first($this->conditions) ? $this->tbl . '.' : '';
            $field1 = $condition[0];
        } elseif (count($parts) > 1) {
            $tbl1 = $parts[0] . '.';
            $field1 = $parts[1];
        }
        $value = count($condition) == 2 ? $condition[1] : $condition[2];
        $tbl2 = '';
        if (! is_numeric($value)) {
            $parts = explode('|', $value);
            if (count($parts) == 1) {
                if (! str_starts_with($value, 'COUNT')) {
                    $tbl2 = '';
                    $value = $parts[0];
                }
            } elseif (count($parts) > 1) {
                $tbl2 = $parts[0] . '.';
                $value = $parts[1];
            }
        }
        return [$tbl1 . $field1 => is_numeric($value) ? $value : $tbl2 . $value];
    }

    private function operator(array $condition) : string
    {
        if (count($condition) === 3) {
            return $condition[1];
        }
        return '=';
    }

    private function tbl(Closure|array $condition, int $tbl = 1) : string
    {
        if ($tbl == 1) {
            $field = $condition[0];
        } elseif ($tbl == 2) {
            $field = count($condition) === 2 ? $condition[1] : $condition[2];
        }
        if (! is_numeric($field)) {
            $parts = explode('|', $field);
            if (count($parts) > 1 && ! in_array($parts[0], ['COUNT'])) {
                return $parts[0];
            }
        }
        return '';
    }

    private function type(Closure|array $condition) : string
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

    private function link(int $key) : string
    {
        $link = 'AND';
        if ($this->method == 'orWhere') {
            $link = 'OR';
            $this->method = '';
        }
        return $link;
    }

    private function braceOpen(int $key, bool $closure = false) : string
    {
        if ($closure && $key == array_key_first($this->conditions)) {
            return '(';
        }
        return '';
    }

    private function braceClose(int $key, bool $closure = false) : string
    {
        if ($closure && $key == array_key_last($this->conditions)) {
            return ')';
        }
        return '';
    }
}