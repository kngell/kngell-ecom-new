<?php

declare(strict_types=1);
abstract class AbstractDataFromCache
{
    protected const TIME = 20;
    protected ?CacheInterface $cache;
    protected ?ModelFactory $factory;
    protected ?Model $model;
    protected array $cachedFiles;
    protected mixed $params;
    protected string $method;
    protected string $cacheFileName;
    protected string $modelName;

    public function __construct(?CacheInterface $cache, ?ModelFactory $factory, ?string $method, string $modelName, mixed $params = null)
    {
        $this->cachedFiles = YamlFile::get('cache_files_list');
        $this->cache = $cache;
        $this->factory = $factory;
        $this->params = $params;
        $this->method = $method;
        $this->modelName = $modelName;
    }

    public function getDataFromCache(?string $fileName) : null|CollectionInterface|stdClass
    {
        $cacheFile = $this->cachedFiles[$fileName] ?? $fileName;
        if (! $this->cache->exists($cacheFile)) {
            $m = $this->model = $this->factory->create($this->modelName);
            $object = null !== $this->params ? $m->{$this->method}($this->params) : $m->{$this->method}();
            $this->cache->set($cacheFile, $object, self::TIME);
        }
        return $this->cache->get($cacheFile);
    }

    /**
     * Get the value of cachedFiles.
     */
    public function getCachedFiles(): array
    {
        return $this->cachedFiles;
    }

    /**
     * Set the value of cachedFiles.
     */
    public function setCachedFiles(array $cachedFiles): self
    {
        $this->cachedFiles = $cachedFiles;

        return $this;
    }

    /**
     * Get the value of cache.
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * Set the value of cache.
     */
    public function setCache(?CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get the value of model.
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * Set the value of model.
     */
    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the value of modelName.
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * Set the value of modelName.
     */
    public function setModelName(string $modelName): self
    {
        $this->modelName = $modelName;

        return $this;
    }
}