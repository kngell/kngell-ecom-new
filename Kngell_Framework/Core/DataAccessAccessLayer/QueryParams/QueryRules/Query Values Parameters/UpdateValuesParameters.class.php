<?php

declare(strict_types=1);

class UpdateValuesParameters extends ValuesParameters
{
    private ValuesParameters $valuesParams;

    public function __construct(ValuesParameters $valuesParams)
    {
        $this->valuesParams = $valuesParams;
    }

    public function getValues(Entity $entity, ?int $enKey = null): ?string
    {
        $entity->disablePrimaryKey();
        $data = $entity->getInitializedAttributes();
        $row = '';
        $rows = '';
        foreach ($data as $field => $value) {
            $sep = $value == end($data) ? '' : ', ';
            $v = $field . ' = :' . $field . $enKey . $sep;
            $this->parameters[$field . $enKey] = $value;
            $row .= $v;
        }
        $rows .= $row;
        $this->valuesParams->setParameters($this->parameters);
        return $rows;
    }
}