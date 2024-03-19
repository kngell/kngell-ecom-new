<?php

declare(strict_types=1);

class SlidersFromCache extends AbstractDataFromCache implements DataFromCacheInterface
{
    private const CACHEFILE = 'slider';
    private const MODEL = SlidersManager::class;
    protected string $method = 'all';

    public function __construct(CacheInterface $cache, ModelFactory $factory)
    {
        parent::__construct($cache, $factory, $this->method, self::MODEL);
    }

    public function get(): null|CollectionInterface|stdClass
    {
        return $this->getDataFromCache(self::CACHEFILE);
    }
}