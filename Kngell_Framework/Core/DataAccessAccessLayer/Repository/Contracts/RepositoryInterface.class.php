<?php

declare(strict_types=1);

interface RepositoryInterface
{
    /**
     * Create or inert into a database
     * ---------------------------------------------------------.
     * @return DataMapperInterface
     */
    public function create() : DataMapperInterface;

    /**
     * Update
     * --------------------------------------------------------.
     * @return DataMapperInterface|null
     */
    public function update() : ?DataMapperInterface;

    /**
     * Delete from database
     * -------------------------------------------------------.
     * @return mixed
     */
    public function delete() : DataMapperInterface;

    /**
     * --------------------------------------------------------
     * Find by ID.
     * @param int $id
     * @return DataMapperInterface
     */
    public function findByID(int $id) : DataMapperInterface;

    /**
     * Find All.
     * --------------------------------------------------------.
     * @return array
     */
    public function findAll() : mixed;

    /**
     * Read Data from database.
     * --------------------------------------------------------.
     * @return mixed
     */
    public function findBy() : mixed;

    /**
     * Find One by
     *-----------------------------------------------------.
     * @param array $conditions
     * @param array $options
     * @return mixed
     */
    public function findOneBy(array $conditions, array $options) : mixed;

    /**
     * Find Object
     *-----------------------------------------------------.
     * @param array $conditions
     * @param array $selectors
     * @return object
     */
    public function findObjectBy(array $conditions = [], array $selectors = []) : Object;

    /**
     * Search data
     *-----------------------------------------------------.
     * @param array $selectors
     * @param array $conditions
     * @param array $parameters
     * @param array $options
     * @return array
     */
    public function findBySearch(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) :array;

    /**
     * Find by Id and Delete
     *------------------------------------------------------.
     * @param array $conditions
     * @return bool
     */
    public function findByIDAndDelete(array $conditions) :bool;

    /**
     * Find by id and update
     *------------------------------------------------------.
     * @param array $fields
     * @param int $id
     * @return bool
     */
    public function findByIdAndUpdate(array $fields = [], int $id = 0) : bool;

    /**
     * find and return self for chanability
     *------------------------------------------------------.
     * @param int $id
     * @param array $selectors
     * @return self
     */
    public function findAndReturn(int $id, array $selectors = []) : self;

    /**
     * Get Table columns.
     *
     * @param array $options
     * @return object
     */
    public function get_tableColumn(array $options): object;

    public function countRecords(array $conditions, ?string $fields): int;

    public function beginTransaction() : bool;

    public function exec(string $sql) : int|false;

    public function customQuery(string $query = '', array $conditions = []) : mixed;

    public function inTransaction() : bool;

    public function rollBack() : bool;

    public function release(array $selectors = [], array $conditions = [], array $parameters = [], array $options = []) : mixed;
}