<?php

declare(strict_types=1);

class ProductsFromCache extends AbstractDataFromCache implements DataFromCacheInterface
{
    private const CACHEFILE = 'products';
    private const MODEL = ProductsManager::class;
    protected string $method = 'getProducts';
    protected mixed $params = 2;

    public function __construct(?CacheInterface $cache, ?ModelFactory $factory = null, null|array $options = null)
    {
        if (null !== $options) {
            $this->method = $options['method'];
            $this->params = $options['args'];
            $this->cacheFileName = $options['cacheFileName'];
        }

        parent::__construct($cache, $factory, $this->method, self::MODEL, $this->params);
    }

    public function get(): null|CollectionInterface|stdClass
    {
        return $this->getDataFromCache($this->cacheFileName ?? self::CACHEFILE);
    }
}