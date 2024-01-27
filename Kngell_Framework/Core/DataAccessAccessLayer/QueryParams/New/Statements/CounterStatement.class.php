<?php

declare(strict_types=1);

class CounterStatement extends AbstractQueryStatement
{
    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            foreach ($childs as $child) {
                [$param] = $child->proceed();
            }
        }
        return [$param] ?? [];
    }
}