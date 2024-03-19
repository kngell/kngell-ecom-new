<?php

declare(strict_types=1);

class QueryParamsFactory
{
    /**
     * Main constructor
     * ===================================.
     *@return void
     */
    public function __construct(private MainQuery $query, private QueryParamsHelper $helper, private Token $token, private StatementFactory $stFactory)
    {
    }

    /**
     * Create factory
     * ====================================.
     *@return QueryParamsInterface
     */
    public function create() : QueryParamsInterface
    {
        $queryParams = new QueryParams($this->query, $this->helper, $this->token, $this->stFactory);
        if (! $queryParams instanceof QueryBuilderInterface) {
            throw new QueryBuilderExceptions($queryParams . ' is not a valid query builder Object!');
        }
        return $queryParams;
    }
}