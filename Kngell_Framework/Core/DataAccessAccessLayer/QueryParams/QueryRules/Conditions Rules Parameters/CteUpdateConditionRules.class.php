<?php

declare(strict_types=1);

class CteUpdateConditionRules extends ConditionParameters
{
    private ConditionParameters $cdtParams;
    private string $operator = ' = ';

    public function __construct(ConditionParameters $cdtParams)
    {
        $this->cdtParams = $cdtParams;
        $this->helper = $cdtParams->getHelper();
        $this->method = $cdtParams->getMethod();
    }

    public function getRules(array $condition) : string
    {
        if (is_array($condition)) {
            $tableAlias = $this->cdtParams->getTableAlias();
            if (ArrayUtil::isAssoc($condition) && count($tableAlias) == 2) {
                $tables = array_keys($tableAlias);
                $r = '';
                foreach ($condition as $field => $value) {
                    $sep = $field == array_key_last($condition) ? '' : ' AND ';
                    $operator = $this->operator ?? $this->cdtParams->operator($condition);
                    $r .= $tableAlias[$tables[1]] . '.' . $field . $operator . $tableAlias[$tables[0]] . '.' . $field . $sep;
                }
                return $r;
            }
        }
        throw new BadQueryArgumentException('Bad Condition for CTE');
    }
}