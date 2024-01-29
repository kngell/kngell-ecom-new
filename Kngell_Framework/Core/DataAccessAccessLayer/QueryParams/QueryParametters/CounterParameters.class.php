<?php

declare(strict_types=1);

class CounterParameters extends AbstractQueryStatement
{
    private array $params = [];

    public function __construct(array $params = [], ?string $method = null, ?CollectionInterface $children = null, ?QueryParamsHelper $helper = null)
    {
        $this->params = $params;
        $this->method = $method;
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        return [$this->params['params'], [], []];
    }
}