<?php

declare(strict_types=1);

class UpdateFieldsParamsters extends FieldsParameters
{
    private FieldsParameters $FieldsParams;

    public function __construct(FieldsParameters $FieldsParams)
    {
        $this->FieldsParams = $FieldsParams;
    }

    public function get(Entity $entity): ?string
    {
        $entity->disablePrimaryKey();
        $fields = array_keys($entity->getInitializedAttributes());
        if (empty($fields)) {
            throw new BadQueryArgumentException("There's no values to update {$entity->table()} table");
        }
        return implode(', ', $fields);
    }
}