<?php

declare(strict_types=1);

class CteStatement extends AbstractQueryStatement
{
    protected string $statement = 'WITH ';

    public function __construct(?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($method, $baseMethod, $queryType);
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $this->useBrace();
            foreach ($childs as $child) {
                list($fields, $this->parameters, $this->bind_arr) = $child->proceed();
            }
        }
        return [];
    }
}