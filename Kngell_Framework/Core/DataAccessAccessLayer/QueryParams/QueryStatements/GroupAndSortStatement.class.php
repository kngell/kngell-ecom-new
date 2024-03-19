<?php

declare(strict_types=1);

class GroupAndSortStatement extends AbstractQueryStatement
{
    public function __construct(?string $method = null, ?string $baseMethod = null)
    {
        parent::__construct($method, $baseMethod);
        $this->statement = match (true) {
            $this->method == 'groupBy' => ' GROUP BY ',
            $this->method == 'orderBy' => ' ORDER BY ',
            default => '',
        };
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            foreach ($childs as $child) {
                $this->tablesSet($child);
                $gs = $child->proceed();
                $this->tablesGet($child);
                $this->query .= match (true) {
                    is_string($gs[0]) => $gs[0],
                    is_array($gs[0]) => implode(', ', $gs[0]),
                    default => ''
                };
                $this->parameters[] = $gs[1];
                $this->bind_arr = $gs[2];
            }
        }
        return [[$this->statement . $this->statement($this->query)], $this->parameters, $this->bind_arr];
    }
}