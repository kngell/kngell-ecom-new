<?php

declare(strict_types=1);

class UserCartItems extends AbstractDataFromCache implements DataFromCacheInterface
{
    private const CACHEFILE = 'user_cart';
    private const MODEL = CartManager::class;
    protected string $method = 'getUserCart';

    public function __construct(CacheInterface $cache, ModelFactory $factory)
    {
        parent::__construct($cache, $factory, $this->method, self::MODEL);
    }

    public function get(): null|CollectionInterface|stdClass
    {
        return $this->getDataFromCache(self::CACHEFILE);
    }
}