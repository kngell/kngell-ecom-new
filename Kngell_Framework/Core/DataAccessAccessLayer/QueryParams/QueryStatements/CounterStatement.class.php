<?php

declare(strict_types=1);

class CounterStatement extends AbstractQueryStatement
{
    protected string $statement;

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
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
                $param = $child->proceed();
            }
        }
        return [$this->statement . $this->statement($param[0]), [], []];
    }
}