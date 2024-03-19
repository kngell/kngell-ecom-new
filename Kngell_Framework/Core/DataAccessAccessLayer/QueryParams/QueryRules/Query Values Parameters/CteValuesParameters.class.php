<?php

declare(strict_types=1);

class CteValuesParameters extends ValuesParameters
{
    private ValuesParameters $valuesParams;
    private string $colId;

    public function __construct(ValuesParameters $valuesparams)
    {
        $this->valuesParams = $valuesparams;
    }

    public function getValues(Entity $entity, ?int $enKey = null): ?string
    {
        $values = $entity->getInitializedAttributes();
        $row = '';
        $rows = '';
        foreach ($values as $field => $value) {
            $sep = $value == end($values) ? '' : ',';
            $this->parameters[$field . $enKey] = $value;
            $v = ':' . $field . $enKey . $sep;
            $row .= $v;
        }
        $rows .= 'ROW(' . $row . ')';
        $this->valuesParams->setParameters($this->parameters);
        if (! isset($this->colId)) {
            $this->colId = $entity->getColId();
        }
        return $rows;
    }

    // $closure = $this->valuesparams->getParams()['closure'];
    //     if ($closure instanceof Closure) {
    //         return $closure->__invoke();
    //     }
    //     return null;
}