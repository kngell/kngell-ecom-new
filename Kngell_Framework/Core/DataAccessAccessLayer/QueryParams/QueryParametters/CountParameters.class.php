<?php

declare(strict_types=1);

class CountParameters extends AbstractStatementParameters
{
    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        $this->tbl = $this->params['tbl'];
        isset($this->params['alias']) ? $this->alias = $this->params['alias'] : '';
    }

    public function proceed(): array
    {
        $r = $this->params['data'];
        if (is_array($r)) {
            $this->parameters[$this->method] = $r[0];
        // $this->query = implode(', ', $r);
        } elseif (is_string($r)) {
            // $this->query = $r;
            $this->parameters[$this->method] = $r;
        }
        $this->query = ':' . $this->method;
        return [$this->query, $this->parameters, $this->bind_arr];
    }
}