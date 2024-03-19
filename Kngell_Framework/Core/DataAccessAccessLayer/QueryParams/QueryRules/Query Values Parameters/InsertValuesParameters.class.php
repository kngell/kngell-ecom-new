<?php

declare(strict_types=1);

class InsertValuesParameters extends ValuesParameters
{
    private ValuesParameters $valuesparams;

    public function __construct(ValuesParameters $valuesparams)
    {
        $this->valuesparams = $valuesparams;
    }

    public function getValues(Entity $entity, ?int $enKey = null): ?string
    {
        $values = $entity->getInitializedAttributes();
        if ($entity->disablePrimaryKey()) {
            return $this->valuesparams->arrayPrefixer($values, $enKey);
        }
        return null;
    }
}