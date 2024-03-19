<?php

declare(strict_types=1);

class EntityValuesFactory
{
    private ValuesParameters $valuesparams;

    public function __construct(ValuesParameters $valuesparams)
    {
        $this->valuesparams = $valuesparams;
    }

    public function create(string $method) : ?ValuesParameters
    {
        $obj = $this->arrObj($method);
        if ($obj instanceof ValuesParameters) {
            return $obj;
        }
        return null;
    }

    private function arrObj(string $queryType) : ValuesParameters
    {
        if ($queryType == 'INSERT') {
            return new InsertValuesParameters($this->valuesparams);
        }
        if ($queryType == 'UPDATE') {
            return new UpdateValuesParameters($this->valuesparams);
        }
        if ($queryType == 'UPDATECTE') {
            return new CteUpdateValuesParameters($this->valuesparams);
        }
        if ($queryType == 'WITHCTE') {
            return new CteValuesParameters($this->valuesparams);
        }
        // elseif (! array_key_exists('delete', $this->arrObj)) {
        //     $this->arrObj['delete'] = new entityValuesDelete($params);
        // }
    }
}