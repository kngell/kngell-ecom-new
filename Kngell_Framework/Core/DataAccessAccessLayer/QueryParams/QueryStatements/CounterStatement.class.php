<?php

declare(strict_types=1);

class CounterStatement extends AbstractQueryStatement
{
    protected string $statement = '';

    public function __construct(?string $method = null, ?string $baseMethod = null)
    {
        parent::__construct($method, $baseMethod);
        $this->statement = match (true) {
            $this->method == 'limit' => ' LIMIT ',
            $this->method == 'offset' => ' OFFSET ',
            default => '',
        };
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            foreach ($childs as $child) {
                $this->tablesSet($child);
                [$counter,$this->parameters,$this->bind_arr] = $child->proceed();
                $this->tablesGet($child);
                $this->query .= $counter;
            }
        }
        return [$this->statement . $this->statement($this->query), $this->parameters, $this->bind_arr];
    }
}