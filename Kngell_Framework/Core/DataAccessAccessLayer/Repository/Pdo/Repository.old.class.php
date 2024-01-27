<?php

declare(strict_types=1);

class Repository_old extends AbstractRepository implements RepositoryInterface
{
    /**
     * Main constructor
     * ====================================================================.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function entity(Entity|CollectionInterface $entity) : self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Create new entrie
     * ====================================================================.
     * @param array $fields
     * @return DataMapperInterface
     */
    public function create(array $fields = []) : DataMapperInterface
    {
        try {
            return $this->em->create($this->fields());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Delete from data base
     * ====================================================================.
     * @param array $conditions
     * @return DataMapperInterface|null
     */
    public function delete(array $conditions = []) : DataMapperInterface
    {
        try {
            return $this->em->delete($conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(array $conditions) : ?DataMapperInterface
    {
        try {
            return $this->em->update($this->fields($conditions), $conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find by ID
     * ====================================================================.
     * @param int $id
     * @return DataMapperInterface
     */
    public function findByID(int $id): DataMapperInterface
    {
        if ($this->isEmpty($id)) {
            try {
                return $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id], []);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    /**
     * Find One element by
     *====================================================================.
     * @param array $conditions
     * @param array $options
     * @return mixed
     */
    public function findOneBy(array $conditions = [], array $options = []) : mixed
    {
        if ($this->isArray($conditions)) {
            try {
                return $this->em->read([], $conditions, [], $options);
            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return false;
    }

    /**
     * Get All
     * ====================================================================.
     * @return array
     */
    public function findAll(): mixed
    {
        try {
            return $this->findBy();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * FindBy
     * ==========================================================.
     * @return mixed
     */
    public function findBy(CollectionInterface $query) : mixed
    {
        try {
            return $this->em->read($selectors, $conditions, $parameters, $options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findObjectBy(array $conditions = [], array $selectors = []): object
    {
        $this->isArray($conditions);
        try {
            return $this->em->getCrud()->get($selectors, $conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Search Data
     *====================================================================.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return array
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []): array
    {
        $this->isArray($conditions);
        try {
            return $this->em->search($selectors, $conditions, $parameters, $options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find data and delete it
     * ====================================================================.
     * @param array $conditions
     * @return bool
     */
    public function findByIDAndDelete(array $conditions): bool
    {
        if ($this->isArray($conditions)) {
            try {
                $result = $this->findOneBy($conditions, []);
                if ($result != null && $result > 0) {
                    $delete = $this->em->delete($conditions);
                    if ($delete) {
                        return true;
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    /**
     * Find and Update
     * ====================================================================.
     * @param array $fields
     * @param int $id
     * @return bool
     */
    public function findByIdAndUpdate(array $fields = [], int $id = 0): bool
    {
        $this->isArray($fields);
        try {
            $result = $id > 0 ? $this->findOneBy([$this->em->getCrud()->getSchemaID() => $id], []) : null;
            if ($result != null && count($result) > 0) {
                $params = (! empty($fields)) ? array_merge([$this->em->getCrud()->getSchemaID() => $id], $fields) : $fields;
                $update = $this->em->update($params, [], $this->em->getCrud()->getSchemaID());
                if ($update) {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find and retur self
     *====================================================================.
     * @param int $id
     * @param array $selectors
     * @return RepositoryInterface
     */
    public function findAndReturn(int $id, array $selectors = []): RepositoryInterface
    {
        return $this;
    }

    public function release(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) : mixed
    {
        try {
            return $this->em->release($selectors, $conditions, $parameters, $options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function get_tableColumn(array $options): object
    {
        try {
            return $this->em->getCrud()->showColumns($options);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function countRecords(array $conditions = [], ?string $fields = ''): int
    {
        $this->isArray($conditions);
        try {
            return $this->em->countRecords($conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function customQuery(string $query = '', array $conditions = []) : mixed
    {
        try {
            return $this->em->customQuery($query, $conditions);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function beginTransaction() : bool
    {
        return $this->em->beginTransaction();
    }

    public function exec(string $sql) : int|false
    {
        return $this->em->exec($sql);
    }

    public function inTransaction() : bool
    {
        return $this->em->inTransaction();
    }

    public function rollBack() : bool
    {
        return $this->em->rollBack();
    }
}