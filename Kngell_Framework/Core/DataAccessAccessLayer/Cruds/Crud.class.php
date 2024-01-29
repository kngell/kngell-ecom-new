<?php

declare(strict_types=1);

use Throwable;

class Crud extends AbstractCrud implements CrudInterface
{
    public function __construct(
        ?DataMapperInterface $dataMapper = null,
        ?QueryBuilderInterface $queryBuilder = null,
        ?string $tableSchema = null,
        ?string $tableSchemaID = null,
        ?array $options = []
    ) {
        parent::__construct($dataMapper, $queryBuilder, $tableSchema, $tableSchemaID, $options);
    }

    public function setProperties(
        DataMapperInterface $dataMapper,
        QueryBuilderInterface $queryBuilder,
        string $tableSchema,
        string $tableSchemaID,
        ?array $options = []
    ) : self {
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    /**
     *@inheritDoc
     */
    public function create(array $fields = []): DataMapperInterface
    {
        try {
            $arg = ['table' => $this->getSchema(), 'type' => 'insert', 'fields' => $fields];
            $query = $this->queryBuilder->buildQuery($arg)->insert();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($fields));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results([], __FUNCTION__);
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function read(?QueryParamsInterface $queryParams = null) : DataMapperInterface
    {
        try {
            $query = $this->queryBuilder->setQueryParams($queryParams)->query();
            $this->dataMapper->setBindArr($this->queryBuilder->getBindAry());
            $options = $queryParams->getQueryParams()['options'];
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters(
                $this->queryBuilder->getParams(),
                ['options' => $options]
            ));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results($options, __FUNCTION__);
            }
            return $this->dataMapper;
        } catch (\Throwable $th) {
            throw new DataAccessLayerException($th->getMessage());
        }
    }

    public function release(array $selectors = [], array $conditions = [], array $params = [], array $options = []) : mixed
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'release',
                'selectors' => $selectors,
                'conditions' => $conditions,
                'params' => $params,
                'extras' => $options,
                'recursive_query' => array_key_exists('recursive', $options) ? $this->recursive_query($options) : '',
            ];
            $query = $this->queryBuilder->buildQuery($arg)->select();
            $this->dataMapper->release($query, $this->dataMapper->buildQueryParameters($arg['conditions'], $params));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results($options, __FUNCTION__);
            }
            return $this->dataMapper;
        } catch (\Throwable $th) {
            throw new DataAccessLayerException($th->getMessage());
        }
    }

    public function showColumns(array $options) : object
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'show',
            ];
            $query = $this->queryBuilder->buildQuery($arg)->showColumn();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters([], []));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results($options, __FUNCTION__);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update data from data base
     * =====================================================================.
     *@inheritDoc
     */
    public function update(array $fields = [], array $conditions = []): DataMapperInterface
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'update',
                'fields' => $fields,
                'where' => $conditions,
            ];
            $query = $this->queryBuilder->buildQuery($arg)->update();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions, $fields));
            return $this->dataMapper->results([], __FUNCTION__);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Delete data from database
     * =====================================================================.
     *@inheritDoc
     */
    public function delete(array $conditions = []): DataMapperInterface
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'delete',
                'conditions' => $conditions,
            ];
            $query = $this->queryBuilder->buildQuery($arg)->delete();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            return $this->dataMapper->results([], __FUNCTION__);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     *@inheritDoc
     */
    public function search(array $selectors = [], array $searchconditions = []) : mixed
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'search',
                'selectors' => $selectors,
                'conditions' => $searchconditions,
            ];
            $query = $this->queryBuilder->buildQuery($arg)->search();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($searchconditions));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @inheritDoc
     */
    public function customQuery(string $query = '', ?array $conditions = []) : mixed
    {
        try {
            $arg = [
                'table' => $this->getSchema(),
                'type' => 'custom',
                'custom' => $query,
                'conditions' => $conditions,
            ];
            $query = $this->queryBuilder->buildQuery($arg)->customQuery();
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function aggregate(string $type, ?string $fields = 'id', array $conditions = []) : mixed
    {
        $args = ['table' => $this->getSchema(), 'primary_key' => $this->getSchemaID(), 'type' => 'select', 'aggregate' => $type, 'aggregate_field' => $fields, 'conditions' => $conditions, 'extras' => ['orderby' => '']];
        $query = $this->queryBuilder->buildQuery($args)->select();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numrow() > 0) {
            return $this->dataMapper->column();
        }
    }

    public function countRecords(array $conditions = [], ?string $fields = 'id'): int
    {
        if ($this->getSchemaID() != '') {
            return empty($conditions) ? $this->aggregate('count', $this->getSchemaID()) : $this->aggregate('count', $this->getSchemaID(), $conditions);
        }
    }

    /**
     * @inheritDoc
     *
     * @param array $selectors
     * @param array $conditions
     * @return object|null
     */
    public function get(array $selectors = [], array $conditions = []) : ?Object
    {
        $args = ['table' => $this->getSchema(), 'type' => 'select', 'selectors' => $selectors, 'conditions' => $conditions];
        $query = $this->queryBuilder->buildQuery($args)->select();
        $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters($conditions));
        if ($this->dataMapper->numrow() >= 0) {
            return $this->dataMapper->result();
        }
    }

    public function beginTransaction() : bool
    {
        return $this->dataMapper->beginTransaction();
    }

    public function exec(string $sql) : int|false
    {
        return $this->dataMapper->exec($sql);
    }

    public function inTransaction() : bool
    {
        return $this->dataMapper->inTransaction();
    }

    public function rollBack() : bool
    {
        return $this->dataMapper->rollBack();
    }

    /**
     * Get the value of _con.
     */
    public function getCon(): DatabaseConnexionInterface
    {
        return $this->dataMapper->getCon();
    }
}
