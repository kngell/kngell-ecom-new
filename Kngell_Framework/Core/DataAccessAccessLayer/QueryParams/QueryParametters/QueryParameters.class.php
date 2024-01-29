<?php

declare(strict_types=1);

class QueryParameters extends AbstractQueryStatement
{
    private array $params = [];

    public function __construct(array $params = [], ?string $method = null, ?CollectionInterface $children = null, ?QueryParamsHelper $helper = null)
    {
        $this->params = $params;
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        $res = '';
        $tbl = isset($this->params['tbl']) ? $this->params['tbl'] : '';
        $alias = isset($this->params['alias']) ? $this->params['alias'] : '';
        $prefix = $this->prefix();
        if ($alias !== '' && $tbl !== '') {
            $res = $prefix . $tbl . ' AS ' . $alias;
        }
        return [$res, [], []];
    }

    private function prefix() : string
    {
        return match (true) {
            $this->method == 'from' => ' FROM ',
            $this->method == 'join' => ' ' . $this->params['joinRule'] . ' ',
            default => '',
        };
    }
}