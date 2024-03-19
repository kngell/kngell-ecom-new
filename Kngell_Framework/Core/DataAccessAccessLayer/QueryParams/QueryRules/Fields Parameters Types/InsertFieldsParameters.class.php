<?php

declare(strict_types=1);

class InsertFieldsParameters extends FieldsParameters
{
    private FieldsParameters $FieldsParams;

    public function __construct(FieldsParameters $FieldsParams)
    {
        $this->FieldsParams = $FieldsParams;
    }

    public function get(Entity $entity): ?string
    {
        $queryType = $this->FieldsParams->getQueryType();
        if (! $queryType == 'WITHCTE') {
            $entity->disablePrimaryKey();
        }
        $fields = array_keys($entity->getInitializedAttributes());
        return implode(', ', $fields);
    }
}