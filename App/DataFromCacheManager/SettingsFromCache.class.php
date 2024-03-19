<?php

declare(strict_types=1);

class SettingsFromCache extends AbstractDataFromCache implements DataFromCacheInterface
{
    const CACHEFILE = 'settings';
    private const MODEL = SettingsManager::class;
    protected string $method = 'getSettings';

    public function __construct(?CacheInterface $cache, ?ModelFactory $factory)
    {
        parent::__construct($cache, $factory, $this->method, self::MODEL);
    }

    public function get(): null|CollectionInterface|stdClass
    {
        return $this->getDataFromCache(self::CACHEFILE);
    }
}