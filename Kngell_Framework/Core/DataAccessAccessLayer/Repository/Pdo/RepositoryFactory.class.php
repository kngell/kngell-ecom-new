<?php

declare(strict_types=1);

class RepositoryFactory
{
    protected string $tableSchema;
    protected string $tableSchemaID;
    protected string $crudIdentifier;
    protected Entity $entity;

    /**
     * Main constructor
     * ==================================================================.
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Create Data Repository
     *==================================================================.
     * @param string $datarepositoryString
     * @return RepositoryInterface
     */
    public function create() : RepositoryInterface
    {
        $repositoryObject = Application::diget(RepositoryInterface::class, [
            'em' => $this->initializeDataAccessManager(),
        ]);
        if (! $repositoryObject instanceof RepositoryInterface) {
            throw new BaseUnexpectedValueException(get_class($repositoryObject) . ' is not a valid repository Object!');
        }
        return $repositoryObject;
    }

    public function initializeDataAccessManager()
    {
        return Application::diget(
            DataAccessLayerManager::class,
            [
                'tableSchema' => $this->tableSchema,
                'tableSchemaID' => $this->tableSchemaID,
                'options' => [],
            ]
        )->initialize();
    }
}