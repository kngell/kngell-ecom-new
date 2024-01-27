<?php

declare(strict_types=1);

class QueryBuilderFactory
{
    private CollectionInterface $queryParams;

    /**
     * Main constructor
     * ============================================================.
     *@return void
     */
    public function __construct(CollectionInterface $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Create factory
     * =============================================================.
     *@return QueryBuilderInterface
     */
    public function create() : QueryBuilderInterface
    {
        $queryBuilder = new QueryBuilder($this->queryParams);
        if (! $queryBuilder instanceof QueryBuilderInterface) {
            throw new QueryBuilderExceptions($queryBuilder . ' is not a valid query builder Object!');
        }

        return $queryBuilder;
    }
}