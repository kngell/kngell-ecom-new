<?php

declare(strict_types=1);

class CrudFactory
{
    public function __construct(private CrudInterface $crud)
    {
    }

    public function create(
        DataMapperInterface $dataMapper,
        QueryBuilderInterface $queryBuilder,
        string $tableSchema,
        string $tableSchemaID,
        ?array $options = []
    ) : CrudInterface {
        return $this->crud->setProperties($dataMapper, $queryBuilder, $tableSchema, $tableSchemaID, $options);
    }
}