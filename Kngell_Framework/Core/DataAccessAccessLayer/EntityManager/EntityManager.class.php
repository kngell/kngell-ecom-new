<?php

declare(strict_types=1);

class EntityManager implements EntityManagerInterface
{
    /**
     * Main constructor
     * =====================================================================.
     * @param CrudInterface $crud
     * @return void
     */
    public function __construct(private CrudInterface $crud)
    {
        $this->crud = $crud;
    }

    public function lastID(): int
    {
        return $this->crud->lastID();
    }

    public function create(): DataMapperInterface
    {
        return $this->crud->create();
    }

    public function read(): DataMapperInterface
    {
        return $this->crud->read();
    }

    public function update(): DataMapperInterface
    {
        return $this->crud->update();
    }

    public function delete(): DataMapperInterface
    {
        return $this->crud->delete();
    }

    public function release(array $selectors = [], array $conditions = [], array $params = [], array $options = []): mixed
    {
        return $this->crud->release($selectors, $conditions, $params, $options);
    }

    public function search(array $selectors = [], array $searchconditions = []) : mixed
    {
        return $this->crud->search($selectors);
    }

    public function customQuery(string $query = '', array $conditions = []): mixed
    {
        return $this->crud->customQuery($query, $conditions);
    }

    public function aggregate(string $type, ?string $fields = 'id', array $conditions = []) : mixed
    {
        return $this->crud->aggregate($type, $fields, $conditions);
    }

    public function countRecords(array $conditions = [], ?string $fields = 'id'): int
    {
        return $this->crud->countRecords($conditions, $fields);
    }

    /**
     * Get Crud Operations
     * =====================================================================.
     *@inheritDoc
     */
    public function getCrud(): CrudInterface
    {
        return $this->crud;
    }

    public function beginTransaction() : bool
    {
        return $this->crud->beginTransaction();
    }

    public function exec(string $sql) : int|false
    {
        return $this->crud->exec($sql);
    }

    public function inTransaction() : bool
    {
        return $this->crud->inTransaction();
    }

    public function rollBack() : bool
    {
        return $this->crud->rollBack();
    }
}