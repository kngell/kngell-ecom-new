<?php

declare(strict_types=1);

class Cache extends AbstractCache
{
    /**
     * Main class constructor.
     */
    public function __construct(?string $cacheIdentifier, ?CacheStorageInterface $storage, array $options)
    {
        parent::__construct($cacheIdentifier, $storage, $options);
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl
     * @return bool
     * @throws CacheException
     */
    public function set(string $key, mixed $value, int|null $ttl = null): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->setCache($key, serialize($value), $ttl);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache repository.', 0, $throwable);
        }

        return true;
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws CacheException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $data = $this->storage->getCache($key);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        if ($data === false) {
            return $default;
        }

        return unserialize((string) $data);
    }

    /**
     * @inheritDoc
     *
     * @param string $key
     * @return bool
     * @throws CacheException
     */
    public function delete(string $key): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        try {
            $this->storage->removeCache($key);
        } catch (Throwable $throwable) {
            throw new CacheException('An exception was thrown in retrieving the key from the cache backend.', 0, $throwable);
        }
        return true;
    }

    /**
     * @inheritDoc
     *
     * @return bool
     */
    public function clear(): bool
    {
        $this->storage->flush();
        return true;
    }

    /**
     * @inheritDoc
     *
     * @param iterable $keys
     * @param mixed $default
     * @return iterable
     * @throws CacheException
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * @inheritDoc
     *
     * @param iterable $values
     * @param int|null $ttl
     * @return bool
     * @throws CacheException
     */
    public function setMultiple(iterable $values, int|null $ttl = null): bool
    {
        $all = true;
        foreach ($values as $key => $value) {
            $all = $this->set($key, $value, $ttl) && $all;
        }

        return $all;
    }

    /**
     * @inheritdoc
     * @param iterable $keys
     * @return bool
     * @throws CacheException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * @inheritdoc
     *
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $this->ensureCacheEntryIdentifierIsvalid($key);
        return $this->storage->hasCache($key);
    }
}