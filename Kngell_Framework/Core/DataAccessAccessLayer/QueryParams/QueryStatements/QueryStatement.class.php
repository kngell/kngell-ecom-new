<?php

declare(strict_types=1);

class QueryStatement extends AbstractQueryStatement
{
    protected string $statement;

    public function __construct(?string $method = null, ?string $baseMethod = null)
    {
        parent::__construct($method, $baseMethod);
        $this->statement = $this->statementString();
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $this->useBrace();
            $this->updateStatement();
            foreach ($childs as $child) {
                $this->tablesSet($child);
                $sep = $child === end($childs) ? '' : ' ';
                list($qs, $this->parameters, $this->bind_arr) = $child->proceed();
                $this->tablesGet($child);
                $this->query .= $qs . $sep;
            }
        }
        return [$this->statement . $this->braceOpen . $this->query . $this->braceClose, $this->parameters, $this->bind_arr];
    }

    private function statementString() : string
    {
        $es = $this->method == 'updateCte' ? ' ' : '';
        return match (true) {
            $this->method == 'from' && $this->baseMethod !== null => ' FROM ',
            $this->method == 'join' && $this->baseMethod !== null => ' INNER JOIN ',
            $this->method == 'leftJoin' && $this->baseMethod !== null => ' LEFT JOIN ',
            $this->method == 'rightJoin' && $this->baseMethod !== null => ' RIGHT JOIN ',
            $this->method == 'insert' && $this->baseMethod !== null => 'INSERT INTO ',
            in_array($this->method, ['update', 'updateCte']) && $this->baseMethod !== null => $es . 'UPDATE ',
            $this->method == 'delete' && $this->baseMethod !== null => 'DELETE FROM ',
            $this->method == 'withCte' && $this->baseMethod !== null => 'WITH ',
            $this->method == 'select' && $this->baseMethod !== null => 'SELECT ',
            default => '',
        };
    }

    private function updateStatement() : void
    {
        if ($this->parent instanceof self) {
            $this->parent->setStatement('');
        }
    }
}