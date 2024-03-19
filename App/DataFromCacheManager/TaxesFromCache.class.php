<?php

declare(strict_types=1);

class TaxesFromCache extends AbstractDataFromCache implements DataFromCacheInterface
{
    private const CACHEFILE = 'taxes';
    private const MODEL = TaxesManager::class;
    protected string $method = 'getTaxSystem';

    public function __construct(CacheInterface $cache, ModelFactory $factory, ?array $options = null)
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
        return $this->getDataFromCache(self::CACHEFILE);
    }
}