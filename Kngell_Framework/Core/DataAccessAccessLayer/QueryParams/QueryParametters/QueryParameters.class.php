<?php

declare(strict_types=1);

class QueryParameters extends AbstractStatementParameters
{
    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        isset($this->params['tbl']) ? $this->tbl = $this->params['tbl'] : '';
        isset($this->params['alias']) ? $this->alias = $this->params['alias'] : '';
        isset($this->params['cte']) ? $this->cte = $this->params['cte'] : '';
    }

    public function proceed(): array
    {
        $alias = '';
        $this->alias = $this->tableAlias($this->tbl);
        if (in_array($this->queryType, ['SELECT', 'UPDATECTE'])) {
            $alias = ' AS ' . $this->alias;
        }
        $this->query = $this->table() . $alias;
        return [$this->query, $this->parameters, $this->bind_arr];
    }

    // private function table() : string
    // {
    //     if (isset($this->tbl)) {
    //         $parts = explode('|', $this->tbl);
    //         return $parts[0];
    //     }
    //     throw new BadQueryArgumentException('No table define');
    // }
}