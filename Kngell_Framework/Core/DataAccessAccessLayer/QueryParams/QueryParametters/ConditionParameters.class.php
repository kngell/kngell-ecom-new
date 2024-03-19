<?php

declare(strict_types=1);

class ConditionParameters extends AbstractStatementParameters
{
    public function __construct(array $params = [], ?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($params, $method, $baseMethod, $queryType);
        $this->tbl = $this->params['tbl'];
        isset($this->params['alias']) ? $this->alias = $this->params['alias'] : '';
    }

    public function proceed(): array
    {
        list($rule, $params, $bind) = $this->closureCheck();
        if (empty($rule)) {
            $condition = isset($this->params['data']) ? $this->params['data'] : [];
            $factory = new ConditionRulesFactory($this);
            $cdtRule = $factory->create($this->queryType, $this->method);
            $rule = $cdtRule->getRules($condition);
        }
        $this->parameters = array_merge($this->parameters, $params);
        $this->bind_arr = array_merge($this->bind_arr, $bind);
        $this->query = $this->braceOpen() . $rule . $this->braceClose();
        return [$this->query, $this->parameters, $this->bind_arr];
    }

    public function getRules(array $condition) : string
    {
        return '';
    }

    /**
     * Get the value of params.
     */
    public function getParams(): array
    {
        return $this->params;
    }
}