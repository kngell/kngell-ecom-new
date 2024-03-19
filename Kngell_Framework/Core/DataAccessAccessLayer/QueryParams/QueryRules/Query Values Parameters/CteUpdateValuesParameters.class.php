<?php

declare(strict_types=1);

class CteUpdateValuesParameters extends ValuesParameters
{
    private ValuesParameters $valuesParams;
    private string $colId;

    public function __construct(ValuesParameters $valuesparams)
    {
        $this->valuesParams = $valuesparams;
    }

    public function getValues(Entity $entity, ?int $enKey = null): ?string
    {
        $entity->disablePrimaryKey();
        $fields = array_keys($entity->getInitializedAttributes());
        $tableAlias = $this->valuesParams->getTableAlias();
        $tbl = $entity->table();
        $row = '';
        $rows = '';
        foreach ($fields as $field) {
            $sep = $field == end($fields) ? '' : ',';
            if (! array_key_exists('cteTable', $tableAlias)) {
                throw new BadQueryArgumentException('CTE Table not define');
            }
            $v = $tableAlias[$tbl] . '.' . $field . ' = ' . $tableAlias['cteTable'] . '.' . $field . $sep;
            $row .= $v;
        }
        $rows .= $row;
        return $rows;
    }
}