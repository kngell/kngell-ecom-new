<?php

declare(strict_types=1);

interface CrudInterface
{
    /**
     * ---------------------------------------------------------------
     * Get Last Insert ID.
     * @return int
     */
    public function lastID():Int;

    /**
     * --------------------------------------------------------------
     * Insert in data base successfully or not.
     * @return DataMapperInterface
     */
    public function create(): DataMapperInterface;

    /**
     * Read Data from data base.
     * --------------------------------------------------------------.
     * @return DataMapperInterface
     */
    public function read() : DataMapperInterface;

    /**
     * ---------------------------------------------------------------
     * Update data.
     * @return DataMapperInterface
     */
    public function update() : DataMapperInterface;

    /**
     * ---------------------------------------------------------------
     * Delete data.
     * @return DataMapperInterface
     */
    public function delete() :DataMapperInterface;

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
    ) : self;

    public function beginTransaction() : bool;

    public function exec(string $sql) : int|false;

    public function inTransaction() : bool;

    public function rollBack() : bool;

    public function release(array $selectors = [], array $conditions = [], array $params = [], array $options = []) : mixed;
}