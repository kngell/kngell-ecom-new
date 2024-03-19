<?php

declare(strict_types=1);

class FieldsStatement extends AbstractQueryStatement
{
    protected string $statement = '';

    public function __construct(?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($method, $baseMethod, $queryType);
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $this->useBrace();
            foreach ($childs as $key => $child) {
                $sep = $this->separator($childs, $key);
                $this->tablesSet($child);
                [$selector,$this->parameters,$this->bind_arr] = $child->proceed();
                $this->tablesGet($child);
                $this->query .= $this->braceOpen . $selector . $this->braceClose . $sep;
            }
        }
        $as = $this->queryType == 'WITHCTE' ? 'AS ' : '';
        return [$this->statement . $this->query . $as, $this->parameters, $this->bind_arr];
    }

    private function separator(array $childs, int $key) : string
    {
        if ($key == array_key_last($childs)) {
            return '';
        }
        return ', ';
    }
}