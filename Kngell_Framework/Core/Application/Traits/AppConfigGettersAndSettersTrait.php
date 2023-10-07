<?php

declare(strict_types=1);

trait AppConfigGettersAndSettersTrait
{
    /**
     * Set the default theming qualified namespace.
     *
     * @param string $theme
     * @return void
     */
    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Returns the theme qualified namespace.
     *
     * @return string
     */
    public function getTheme(): string
    {
        return isset($this->theme) ? $this->theme : '';
    }

    /**
     * Set the application main configuration from the project app.yml file.
     *
     * @param array $ymlApp
     * @return self
     */
    public function setConfig(array $ymlApp): self
    {
        $this->appConfig = $ymlApp;
        return $this;
    }

    /**
     * Return the application configuration as an array of data.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public function getErrorHandling(): array
    {
        return $this->errorHandling;
    }

    public function getErrorHandlerLevel(): mixed
    {
        return $this->errorLevel;
    }

    /**
     * @return string
     */
    public function getLogger(): string
    {
        return $this->handler;
    }

    /**
     * @return string
     */
    public function getLoggerFile(): string
    {
        return $this->logFile;
    }

    /**
     * @return array
     */
    public function getLoggerOptions(): array
    {
        return $this->logOptions;
    }

    /**
     * @return string
     */
    public function getLogMinLevel(): string
    {
        return $this->logMinLevel;
    }

    public function setControllerArray(array $crtl) : self
    {
        $this->controllerArray = $crtl;

        return $this;
    }

    public function getControllerArray() : array
    {
        return $this->controllerArray;
    }

    /**
     * Returns the application route configuration array.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        if (count($this->routes) < 0) {
            throw new BaseInvalidArgumentException('No routes detected within your routes.yml file');
        }

        return $this->routes;
    }

    /**
     * Returns the application route handler mechanism.
     *
     * @return string
     */
    public function getRouteHandler(): string
    {
        if ($this->routeHandler === null) {
            throw new BaseInvalidArgumentException('Please set your route handler.');
        }
        return $this->routeHandler;
    }

    /**
     * Get the new router object fully qualified namespace.
     *
     * @return string
     */
    public function getRouter(): string
    {
        if ($this->newRouter === null) {
            throw new BaseInvalidArgumentException('No new router object was defined.');
        }
        return $this->newRouter;
    }

    /**
     * Returns the cache configuration array.
     *
     * @return string
     */
    public function getCacheIdentifier(): string
    {
        return $this->cache['cache_name'] ?? '';
    }

    /**
     * Returns the cache configuration array.
     *
     * @return array
     */
    public function getCache(): array
    {
        return $this->cache;
    }

    /**
     * Set the application container providers configuration from the session.yml file.
     *
     * @param array $ymlProviders
     * @return self
     */
    public function setContainerProviders(array $ymlProviders): self
    {
        $this->containerProviders = $ymlProviders;
        return $this;
    }

    /**
     * Returns the container providers configuration array.
     *
     * @return array
     */
    public function getContainerProviders(): array
    {
        return $this->containerProviders;
    }

    /**
     * Returns the cookie configuration array.
     *
     * @return array
     */
    public function getCookie(): array
    {
        return $this->cookie;
    }

    /**
     * If session yml is set from using the setSession from the application
     * bootstrap. Use the user defined session.yml else revert to the core
     * session configuration.
     *
     * @return array
     * @throws BaseInvalidArgumentException
     */
    public function getSession(): array
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->session;
    }

    /**
     * Returns the default session driver from either the core or user defined
     * session configuration. Throws an exception if neither configuration
     * was found.
     *
     * @return string
     * @throws BaseInvalidArgumentException
     */
    public function getSessionDriver(): string
    {
        if (empty($this->session)) {
            throw new BaseInvalidArgumentException('You have no session configuration. This is required.');
        }
        return $this->newSessionDriver;
    }

    /**
     * Get the value of isSessionGlobal.
     */
    public function getIsSessionGlobal(): bool
    {
        return $this->isSessionGlobal;
    }

    /**
     * Set the value of isSessionGlobal.
     */
    public function setIsSessionGlobal(bool $isSessionGlobal): self
    {
        $this->isSessionGlobal = $isSessionGlobal;
        return $this;
    }

    /**
     * Get the value of globalSessionKey.
     */
    public function getGlobalSessionKey(): ?string
    {
        return $this->globalSessionKey;
    }

    /**
     * Set the value of globalSessionKey.
     */
    public function setGlobalSessionKey(?string $globalSessionKey): self
    {
        $this->globalSessionKey = $globalSessionKey;

        return $this;
    }

    /**
     * Get the value of isCacheGlobal.
     */
    public function getIsIsCacheGlobal(): bool
    {
        return $this->isCacheGlobal;
    }

    /**
     * Set the value of isCacheGlobal.
     */
    public function setIsCacheGlobal(bool $isCacheGlobal): self
    {
        $this->isCacheGlobal = $isCacheGlobal;

        return $this;
    }

    /**
     * Get the value of globalCacheKey.
     */
    public function getGlobalCacheKey(): ?string
    {
        return $this->globalCacheKey;
    }

    /**
     * Set the value of globalCacheKey.
     */
    public function setGlobalCacheKey(?string $globalCacheKey): self
    {
        $this->globalCacheKey = $globalCacheKey;

        return $this;
    }
}