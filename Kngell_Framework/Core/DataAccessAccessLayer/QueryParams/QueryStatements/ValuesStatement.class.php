<?php

declare(strict_types=1);
class ValuesStatement extends AbstractQueryStatement
{
    protected string $statement;

    public function __construct(?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($method, $baseMethod, $queryType);
        $this->statement = match (true) {
            in_array($this->queryType, ['INSERT', 'WITHCTE']) => 'VALUES ',
            in_array($this->queryType, ['UPDATE', 'UPDATECTE']) => ' SET ',
        };
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $this->useBrace();
            foreach ($childs as $key => $child) {
                $sep = $key == array_key_last($childs) ? '' : ',';
                $this->tablesSet($child);
                [$values,$this->parameters,$this->bind_arr] = $child->proceed();
                $this->tablesGet($child);
                $this->query .= $values . $sep;
            }
        }
        $this->cteQueryValues();
        return [$this->statement . $this->braceOpen . $this->query . $this->braceClose, $this->parameters, $this->bind_arr];
    }

    protected function useBrace() : void
    {
        if (in_array($this->queryType, ['INSERT', 'WITHCTE'])) {
            $this->braceOpen = ' (';
            $this->braceClose = ') ';
        }
    }

    private function cteQueryValues() : void
    {
        if ($this->queryType == 'WITHCTE') {
            $this->query = '( ' . $this->statement . $this->query . ' )';
            $this->braceOpen = '';
            $this->braceClose = '';
            $this->statement = '';
        }
    }

    private function queryUpdate(ValuesParameters $child) : string
    {
        $update = '';
        if ($this->queryType == 'UPDATE') {
            $this->query = $this->statement . $this->query;
            $this->statement = '';
            $update = $child->update();
        }
        return $update;
    }
}