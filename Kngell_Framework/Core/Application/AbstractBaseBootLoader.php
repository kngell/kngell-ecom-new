<?php

declare(strict_types=1);

abstract class AbstractBaseBootLoader extends Container implements ApplicationInterface
{
    protected ?AppConfigSetup $appConfig = null;
    protected ?RooterInterface $rooter = null;

    /**
     * Returns the bootstrap appplications object.
     *
     * @return Application
     */
    public function app(): Application
    {
        return self::getInstance();
    }

    /**
     * Return the session global variable through a static method which should make
     * accessing the global variable much more simplier
     * returns the session object.
     *
     * @return object
     */
    public static function getSessionObject(): Object
    {
        return GlobalManager::get('session_global');
    }

    /**
     * Initialise the pass our classes to be loaded by the framework dependency
     * container using PHP Reflection.
     *
     * @param string $dependencies
     * @param array $args
     * @return mixed
     */
    public static function diGet(string $class, array $args = []): mixed
    {
        return self::getInstance()->make($class, $args);
    }

    public function rooter() : RooterInterface
    {
        return $this->rooter;
    }

    public function loadRoutes() : RooterInterface
    {
        return $this->rooter = $this->make(RooterFactory::class, [
            'routes' => $this->appConfig->getRoutes(),
            'controllerProperties' => $this->loadProviders(),
        ])->create();
    }

    /**
     * Turn on global session from public/index.php bootstrap file to make the session
     * object available globally throughout the application using the GlobalManager object.
     * @return bool
     */
    protected function isSessionGlobal(): bool
    {
        return $this->appConfig->isSessionGlobal();
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    // protected function bindPathsInContainer()
    // {
    //     $this->instance('path.appRoot', $this->getPath());
    // }

    /**
     * Compare PHP version with the core version ensuring the correct version of
     * PHP and MagmaCore framework is being used at all time in sync.
     *
     * @return void
     */
    protected function phpVersion(): void
    {
        if (version_compare($phpVersion = PHP_VERSION, $coreVersion = $this->appConfig->getConfig()['app']['app_version'], '<')) {
            die(sprintf('You are runninig PHP %s, but the core framework requires at least PHP %s', $phpVersion, $coreVersion));
        }
    }

    /**
     * Load the framework default enviornment configuration. Most configurations
     * can be done from inside the app.yml file.
     *
     * @return void
     */
    protected function loadEnvironment(): void
    {
        $settings = $this->appConfig->getConfig()['settings'];
        ini_set('default_charset', $settings['default_charset']);
        date_default_timezone_set($settings['default_timezone']);
    }

    /**
     * Returns an array of the application set providers which will be loaded
     * by the dependency container. Which uses PHP Reflection class to
     * create objects. With a key property which is defined within the yaml
     * providers file.
     *
     * @return array
     */
    protected function loadProviders(): array
    {
        return $this->appConfig->getContainerProviders();
    }

    protected function loadControllerArray(): array
    {
        return $this->appConfig->getControllerArray();
    }

    protected function loadSession(): Object
    {
        $session = $this->make(SessionFacade::class, [
            $this->appConfig->getSession()['session_name'],
            $this->appConfig->getSessionDriver(),
            $this->make(SessionEnvironment::class, [$this->appConfig->getSession()]),
        ])->setSession();
        if ($this->isSessionGlobal() === true) {
            GlobalManager::set($this->app()->getGlobalSessionKey(), $session);
        }
        return $session;
    }

    protected function loadCache(): CacheInterface
    {
        $cache = $this->make(CacheFacade::class)->create($this->appConfig->getCacheIdentifier(), $this->appConfig->getCache());
        if ($this->app()->isCacheGlobal() === true) {
            GlobalManager::set($this->app()->getGlobalCacheKey(), $cache);
        }
        return $cache;
    }

    protected function loadCookies()
    {
        return $this->make(CookieFacade::class, [
            'cookieEnvironmentArray' => [],
            'cookieConfig' => $this->make(CookieConfig::class),
            'gv' => $this->make(GlobalVariablesInterface::class),
        ])->initialize();
    }

    protected function loadErrorHandlers(): void
    {
        error_reporting($this->appConfig->getErrorHandlerLevel());
        set_error_handler($this->appConfig->getErrorHandling()['error']);
        set_exception_handler($this->appConfig->getErrorHandling()['exception']);
    }

    protected function run(?string $route = null, array $params = []) : ResponseHandler
    {
        return $this->loadRoutes()->resolve($route, $params);
    }

    protected function registerContainerAliases(array $aliasesGroup = [])
    {
        if (! empty($aliasesGroup) && is_array($aliasesGroup)) {
            foreach ($aliasesGroup as $method => $aliases) {
                if (is_array($aliases) && ! empty($aliases)) {
                    foreach ($aliases as $obj => $value) {
                        if (is_array($value)) {
                            $method == 'bind' ? $this->$method($obj, $value[0], false, $value[1]) : $this->$method($obj, $value[0], $value[1]);
                        } else {
                            $this->$method($obj, $value);
                        }
                    }
                }
            }
        }
    }

    protected function handleCors()
    {
        $this->make(Cors::class)->handle();
        return $this;
    }
}