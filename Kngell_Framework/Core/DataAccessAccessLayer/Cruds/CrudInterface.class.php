<?php

declare(strict_types=1);

interface CrudInterface
{
    /**
     * ---------------------------------------------------------------
     * Get Data base name.
     * @return string
     */
    public function getSchema():String;

    /**
     * ---------------------------------------------------------------
     * Get Primary Key.
     * @return string
     */
    public function getSchemaID():String;

    /**
     * ---------------------------------------------------------------
     * Get Last Insert ID.
     * @return int
     */
    public function lastID():Int;

    /**
     * --------------------------------------------------------------
     * Insert in data base successfully or not.
     * @param array $fields
     * @return DataMapperInterface
     */
    public function create(array $fields = []): DataMapperInterface;

    /**
     * Read Data from data base.
     * --------------------------------------------------------------.
     * @param QueryParamsInterface|null $query
     * @return DataMapperInterface
     */
    public function read(?QueryParamsInterface $queryParams = null) : DataMapperInterface;

    /**
     * ---------------------------------------------------------------
     * Update data.
     * @param array $fields
     * @param array $conditions
     * @return DataMapperInterface
     */
    public function update(array $fields = [], array $conditions = []) : DataMapperInterface;

    /**
     * ---------------------------------------------------------------
     * Delete data.
     * @param array $conditions
     * @return DataMapperInterface
     */
    public function delete(array $conditions = []) :DataMapperInterface;

    /**
     * ---------------------------------------------------------------
     * Search data.
     * @param array $selectors
     * @param array $searchconditions
     * @return mixed
     */
    public function search(array $selectors = [], array $searchconditions = []) : mixed;

    /**
     * ---------------------------------------------------------------
     * Custom Data.
     * @param array $query
     * @param array $conditions
     * @return void
     */
    public function customQuery(string $query = '', array $conditions = []) :mixed;

    /**
     * Aggregate
     * ---------------------------------------------------------------.
     * @param string $type
     * @param string|null $fields
     * @param array $conditions
     * @return mixed
     */
    public function aggregate(string $type, ?string $fields = 'id', array $conditions = []) : mixed;

    /**
     * Count Records
     * ---------------------------------------------------------------.
     * @param array $conditions
     * @param string|null $fields
     * @return int
     */
    public function countRecords(array $conditions = [], ?string $fields = 'id') : int;

    /**
     * Returns a single table row as an object
     * ---------------------------------------------------------------.
     * @param array $selectors = []
     * @param array $conditions = []
     * @return null|object
     */
    public function get(array $selectors = [], array $conditions = []) : ?Object;

    /**
     * Get table columns.
     *
     * @param array $options
     * @return object
     */
    public function showColumns(array $options) : object;

    public function setProperties(
        DataMapperInterface $dataMapper,
        queryBuilderInterface $queryBuilder,
        string $tableSchema,
        string $tableSchemaID,
        ?array $options = []
    ) : self;

    public function beginTransaction() : bool;

    public function exec(string $sql) : int|false;

    public function inTransaction() : bool;

    public function rollBack() : bool;

    public function release(array $selectors = [], array $conditions = [], array $params = [], array $options = []) : mixed;
}
