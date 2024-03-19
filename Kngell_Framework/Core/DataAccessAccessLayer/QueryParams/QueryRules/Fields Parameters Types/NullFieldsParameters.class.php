<?php

declare(strict_types=1);

class NullFieldsParameters extends FieldsParameters
{
    private FieldsParameters $FieldsParams;

    public function __construct(FieldsParameters $FieldsParams)
    {
        $this->FieldsParams = $FieldsParams;
    }

    public function get(Entity $entity): ?string
    {
        return '';
    }
}