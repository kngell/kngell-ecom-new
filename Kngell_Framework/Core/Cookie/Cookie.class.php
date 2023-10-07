<?php

declare(strict_types=1);

class Cookie implements CookieInterface
{
    /** @var CookieStoreInterface */
    protected CookieStoreInterface $cookieStore;

    /**
     * Protected class constructor as this class will be a singleton.
     *
     * @param CookieStoreInterface $cookieStore
     */
    public function __construct(CookieStoreInterface $cookieStore)
    {
        $this->cookieStore = $cookieStore;
    }

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function exists(string $name = ''): bool
    {
        return $this->cookieStore->exists($name);
    }

    public function get(string $name) : mixed
    {
        return $this->cookieStore->getCookie($name);
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return self
     */
    public function set(mixed $value, ?string $cookieName = null): void
    {
        $this->cookieStore->setCookie($value, $cookieName);
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function delete(?string $name = null): void
    {
        if ($this->exists()) {
            $this->cookieStore->deleteCookie($name);
        }
    }

    /**
     * @inheritdoc
     *
     * @return void
     */
    public function invalidate(): void
    {
        foreach ($_COOKIE as $name => $value) {
            if ($this->exists()) {
                $this->cookieStore->deleteCookie($name);
            }
        }
    }
}
