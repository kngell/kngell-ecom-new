<?php

declare(strict_types=1);

class FieldsParametersFactory
{
    private FieldsParameters $fieldsParams;

    public function __construct(FieldsParameters $fieldsParams)
    {
        $this->fieldsParams = $fieldsParams;
    }

    public function create(string $queryType, string $method) : ?FieldsParameters
    {
        $obj = $this->arrObj($queryType, $method);
        if ($obj instanceof FieldsParameters) {
            return $obj;
        }
        return null;
    }

    private function arrObj(string $queryType, string $method) : FieldsParameters
    {
        return match (true) {
            $queryType == 'SELECT' && $method == 'fields' => new SelectFieldsParameters($this->fieldsParams) ,
            $queryType == 'UPDATE' && $method == 'fields' => new UpdateFieldsParamsters($this->fieldsParams),
            in_array($queryType, ['INSERT', 'WITHCTE']) => new InsertFieldsParameters($this->fieldsParams),
            default => new NullFieldsParameters($this->fieldsParams)
        };
    }
}