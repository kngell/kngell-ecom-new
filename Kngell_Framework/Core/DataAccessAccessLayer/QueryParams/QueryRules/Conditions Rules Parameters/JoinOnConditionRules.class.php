<?php

declare(strict_types=1);

class JoinOnConditionRules extends ConditionParameters
{
    private ConditionParameters $cdtParams;

    public function __construct(ConditionParameters $cdtParams)
    {
        $this->cdtParams = $cdtParams;
        $this->helper = $cdtParams->getHelper();
        $this->method = $cdtParams->getMethod();
        $this->baseMethod = $cdtParams->getBaseMethod();
        $this->queryType = $cdtParams->getQueryType();
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

    private function condition(array $condition) : array
    {
        $newCondition = [];
        if (ArrayUtil::isAssoc($condition)) {
            foreach ($condition as $field => $value) {
                $newCondition[] = $field;
                $newCondition[] = $value;
            }
        } else {
            $newCondition = $condition;
        }
        return $newCondition;
    }

    private function rule(array $condition) : string
    {
    }
}
