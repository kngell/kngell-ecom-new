<?php

declare(strict_types=1);

class VisitorsFromCache extends AbstractDataFromCache implements DataFromCacheInterface
{
    private const CACHEFILE = 'visitors';
    private const MODEL = VisitorsManager::class;
    protected string $method = 'getAllVisitors';

    public function __construct(CacheInterface $cache, ModelFactory $factory)
    {
        parent::__construct($cache, $factory, $this->method, self::MODEL);
    }

    public function get(): null|CollectionInterface|stdClass
    {
        return $this->getDataFromCache(self::CACHEFILE);
    }
}