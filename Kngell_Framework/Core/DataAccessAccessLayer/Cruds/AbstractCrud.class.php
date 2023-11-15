<?php

declare(strict_types=1);
abstract class AbstractCrud implements CrudInterface
{
    public function __construct(
        protected ?DataMapperInterface $dataMapper = null,
        protected ?QueryBuilderInterface $queryBuilder = null,
        protected ?string $tableSchema = null,
        protected ?string $tableSchemaID = null,
        protected ?array $options = [],
    ) {
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
        $this->options = $options;
        $this->dataMapper = $dataMapper;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     *@inheritDoc
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLasID();
    }

    /**
     *@inheritDoc
     */
    public function getSchema(): string
    {
        return (string) $this->tableSchema;
    }

    /**
     *@inheritDoc
     */
    public function getSchemaID(): string
    {
        return (string) $this->tableSchemaID;
    }

    protected function recursive_query(array $options = []) : string
    {
        $arg = [
            'table' => $this->getSchema(),
            'type' => 'select',
            'selectors' => $options['recursive']['selectors'],
            'conditions' => $options['recursive']['conditions'],
            'params' => $options['recursive']['parameters'],
            'extras' => $options['recursive']['options'],
        ];
        list($sql, $query) = $this->queryBuilder->buildQuery($arg)->baseQuery();

        return $query;
    }
}