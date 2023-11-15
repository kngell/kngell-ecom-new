<?php

declare(strict_types=1);

class DataAccessLayerManager
{
    // private string $tableSchema;
    // private string $tableSchemaID;
    // private array $options;
    // private DataMapperEnvironmentConfig $dataMapperEnvConfig;
    // private Entity $entity;

    /**
     * Main contructor
     *=====================================================================.
     * @param DataMapperEnvironmentConfig $datamapperEnvConfig
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(
        private string $tableSchema,
        private string $tableSchemaID,
        private array $options,
        private DataMapperEnvironmentConfig $dataMapperEnvConfig,
        private Entity $entity,
        private EntityManagerFactory $entityManagerFactory,
        private QueryBuilderInterface $queryBuilder
    ) {
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
        $this->entity = $entity;
        $this->dataMapperEnvConfig = $dataMapperEnvConfig;
    }

    /**
     * Initializind ORM DataBase Management
     * =====================================================================.
     * @return void
     */
    public function initialize()
    {
        return $this->entityManagerFactory->create(
            $this->tableSchema,
            $this->tableSchemaID,
            $this->options,
            $this->dataMapperEnvConfig,
            $this->queryBuilder
        );
    }
}