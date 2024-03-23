<?php

declare(strict_types=1);

abstract class AbstractModel
{
    protected CacheInterface $cache;
    protected CookieInterface $cookie;
    protected SessionInterface $session;
    protected Entity $entity;
    protected QueryParamsInterface $queryParams;
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

    public function query(?string $queryType = null, ?string $tbl = null, ...$params) : QueryParamsInterface
    {
        if (! isset($this->queryParams)) {
            $this->queryParams = Application::diGet(QueryParamsInterface::class);
            $query = $this->queryParams->setBaseOptions(
                $tbl ?? $this->tableSchema,
                $this->entity
            )->query($queryType, $params);
            Application::getInstance()->bindParameters(EntityManagerFactory::class, [
                'query' => $query,
            ]);
        }
        return $this->queryParams;
    }

    public function repository() : RepositoryInterface
    {
        if (! isset($this->repository)) {
            $this->repository = Application::diGet(RepositoryFactory::class)->create();
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

    public function showColumns(string $table) : string
    {
        $this->query()->raw("DESCRIBE $table")->go();
        return implode('; ', array_column($this->repository()->findBy()->get_results(), 'Field'));
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