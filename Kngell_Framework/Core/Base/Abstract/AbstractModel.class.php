<?php

declare(strict_types=1);

abstract class AbstractModel
{
    protected CacheInterface $cache;
    protected CookieInterface $cookie;
    protected Entity $entity;
    protected QueryParamsInterfaceNew $queryParams;
    protected RepositoryInterface $repository;
    protected ?PDOStatement $_statement;
    protected DatabaseConnexionInterface $_con;
    protected ModelHelper $helper;
    protected Token $token;
    protected ModelFactory $factory;
    protected mixed $_results;
    protected int $_count;
    protected array $cachedFiles;
    protected string $tableSchema;
    protected string $tableSchemaID;

    public function __construct(string $tableSchema, string $tableSchemaID)
    {
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    public function query(?string $queryType = null, ?string $tbl = null, ...$params) : QueryParamsInterfaceNew
    {
        if (! isset($this->queryParams)) {
            $this->queryParams = Application::diGet(QueryParamsInterfaceNew::class);
            $query = $this->queryParams->setBaseOptions(
                $tbl ?? $this->tableSchema,
                $this->entity
            )->query($queryType, $params);
            Application::getInstance()->bindParameters(EntityManagerInterface::class, [
                'query' => $query,
            ]);
        }
        return $this->queryParams;
    }

    public function repository() : RepositoryInterface
    {
        if (! isset($this->repository)) {
            $this->repository = Application::diGet(RepositoryInterface::class);
        }
        return $this->repository;
    }

    /**
     * Get the value of entity.
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * Set the value of entity.
     */
    public function setEntity(Entity $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
}