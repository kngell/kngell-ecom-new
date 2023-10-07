<?php

declare(strict_types=1);

final class Application extends AbstractBaseBootLoader
{
    use SystemTrait;

    //private RooterInterface $rooter;

    /**
     * Main constructor
     * =========================================================.
     * @param string $appRoot
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(?string $route = null, array $params = []) : ResponseHandler
    {
        /* Attempting to run a single instance of the application */
        try {
            $response = $this->setConst()->boot()->run($route, $params);
        } catch (BaseResourceNotFoundException $e) {
            $this->make(ResponseHandler::class, [
                'content' => $e->getMessage(),
                'status' => HttpStatus::getName($e->getCode()),
            ])->prepare($this->make(RequestHandler::class));
            $response = $this->rooter()->resolve('error', [$e]);
        }
        return $response;
    }

    /**
     * Turn on global caching from public/index.php bootstrap file to make the cache
     * object available globally throughout the application using the GlobalManager object.
     * @return bool
     */
    public function isCacheGlobal(): bool
    {
        return $this->appConfig->isCacheGlobal();
        // $isCacheGlobal = $this->appConfig->getIsIsCacheGlobal();
        // return isset($isCacheGlobal) && $isCacheGlobal === true ? true : false;
    }

    /**
     * Turn on global session from public/index.php bootstrap file to make the session
     * object available globally throughout the application using the GlobalManager object.
     * @return bool
     */
    public function isSessionGlobal(): bool
    {
        return $this->appConfig->isSessionGlobal();
        // $isSessionGlobal = $this->appConfig->getIsSessionGlobal();
        // return isset($isSessionGlobal) && $isSessionGlobal === true ? true : false;
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalSessionKey(): string
    {
        if ($this->appConfig->getGlobalSessionKey() !== null && strlen($this->appConfig->getGlobalSessionKey()) < 3) {
            throw new BaseLengthException($this->appConfig->getGlobalSessionKey() . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->appConfig->getGlobalSessionKey() !== null) ? $this->appConfig->getGlobalSessionKey() : 'session_global';
    }

    /**
     * @return string
     * @throws BaseLengthException
     */
    public function getGlobalCacheKey(): string
    {
        if ($this->appConfig->getGlobalCacheKey() !== null && strlen($this->appConfig->getGlobalCacheKey()) < 3) {
            throw new BaseLengthException($this->appConfig->getGlobalCacheKey() . ' is invalid this needs to be more than 3 characters long');
        }
        return ($this->appConfig->getGlobalCacheKey() !== null) ? $this->appConfig->getGlobalCacheKey() : 'cache_global';
    }
}