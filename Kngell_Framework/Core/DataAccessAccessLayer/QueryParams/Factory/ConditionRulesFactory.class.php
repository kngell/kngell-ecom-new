<?php

declare(strict_types=1);

class ConditionRulesFactory
{
    private ConditionParameters $cdtParams;

    public function __construct(ConditionParameters $cdtParams)
    {
        $this->cdtParams = $cdtParams;
    }

    public function create(string $queryType, string $method) : ?ConditionParameters
    {
        $obj = $this->arrObj($queryType, $method);
        if ($obj instanceof ConditionParameters) {
            return $obj;
        }
        return null;
    }

    private function arrObj(string $queryType, string $method) : ConditionParameters
    {
        if ($queryType == 'UPDATECTE' && in_array($method, ['on', 'updateCte'])) {
            return new CteUpdateConditionRules($this->cdtParams);
        } else {
            return new CommonConditionRules($this->cdtParams);
        }
    }
}