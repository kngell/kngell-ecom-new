<?php

declare(strict_types=1);

class CommonConditionRules extends ConditionParameters
{
    private ConditionParameters $cdtParams;

    public function __construct(ConditionParameters $cdtParams)
    {
        $this->cdtParams = $cdtParams;
        $this->helper = $cdtParams->getHelper();
        $this->method = $cdtParams->getMethod();
        $this->baseMethod = $cdtParams->getBaseMethod();
        $this->queryType = $cdtParams->getQueryType();
        $this->tableAlias = $cdtParams->getTableAlias();
        $this->aliasCheck = $cdtParams->getAliasCheck();
    }

    public function getRules(array $condition) : string
    {
        if (is_array($condition)) {
            $condition = $this->condition($condition);
            $rule = $this->rule($condition);
            $this->cdtParams->setParameters($this->parameters);
            $this->cdtParams->setBindArr($this->bind_arr);
            return $rule;
        }
        throw new BadQueryArgumentException('Bad Condition for CTE');
    }

    private function rule(array $condition) : string
    {
        $op = $this->operator($condition);
        [$type,$condition] = $this->parseFields($condition, $this->method);
        $field = $this->field($condition);
        $value = $this->value($condition);
        if ($type == 'exp') {
            return $field . $op . $value;
        }
        $stmt = '';
        if (array_key_exists('field', $condition) && array_key_exists('list', $condition)) {
            $stmt = $value;
        } else {
            $this->parameters[$this->field] = $value;
            $stmt = ':' . $this->field;
        }

        return $field . $op . $stmt;
    }

    private function condition(array $condition) : array
    {
        if (ArrayUtil::is_sequential_array($condition) | str_ends_with($this->method, 'In')) {
            return $condition;
        }
        $newCondition = [];
        if (ArrayUtil::isAssoc($condition)) {
            foreach ($condition as $field => $value) {
                $newCondition[] = $field;
                $newCondition[] = $value;
            }
        }
        return $newCondition;
    }

    private function field(array $condition) : mixed
    {
        return isset($condition['field']) ? $condition['field'] : $condition['0'];
    }

    private function value(array $condition) : mixed
    {
        if (isset($condition['list']) && str_ends_with($this->method, 'In')) {
            $value = $condition['list'];
        } else {
            $value = count($condition) === 2 ? $condition[1] : $condition[2];
        }
        if (is_array($value)) {
            $value = '(' . $this->arrayPrefixer($value) . ')';
        }
        return $value;
    }
}