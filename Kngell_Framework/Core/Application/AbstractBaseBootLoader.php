<?php

declare(strict_types=1);

abstract class AbstractBaseBootLoader extends Container implements ApplicationInterface
{
    protected ?AppConfig $appConfig = null;
    protected ?RooterInterface $rooter = null;

    public function __construct()
    {
        $this->registerBaseAppSingleton();
    }

    /**
     * Set application base Constant.
     *
     * @return self
     */
    public function setConst() : self
    {
        BaseConstants::load($this->app());
        return $this;
    }

    /**
     * Boot Application.
     *
     * @return self
     */
    public function boot() : self
    {
        $this->appConfig = $this->make(AppConfig::class)->create();
        return $this;
    }

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
    public static function getSession(): Object
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

    public function loadCache(): CacheInterface
    {
        $cache = $this->make(CacheFacade::class)->create($this->appConfig->getCacheIdentifier(), $this->appConfig->getCache());
        if ($this->app()->isCacheGlobal() === true) {
            GLobalManager::set($this->app()->getGlobalCacheKey(), $cache);
        }
        return $cache;
    }

    public function rooter() : RooterInterface
    {
        return $this->rooter;
    }

    public function loadRoutes() : RooterInterface
    {
        return $this->rooter = $this->make(RooterFactory::class, [
            'rooter' => $this->make(RooterInterface::class, [
                'controllerProperties' => $this->loadProviders(),
            ]),
            'routes' => $this->appConfig->getRoutes(),
        ])->create();
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

    /**
     * Builds the application session component and returns the configured object. Based
     * on the session configuration array.
     *
     * @return object - returns the session object
     */
    protected function loadSession(): Object
    {
        $session = $this->make(SessionFacade::class, [
            'sessionEnvironment' => $this->appConfig->getSession(),
            'sessionIdentifier' => $this->appConfig->getSession()['session_name'],
            'storage' => $this->appConfig->getSessionDriver(),
        ])->setSession();
        if ($this->app()->isSessionGlobal() === true) {
            GlobalManager::set($this->app()->getGlobalSessionKey(), $session);
        }
        return $session;
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
        $this->phpVersion();
        $this->handleCors();
        $this->loadErrorHandlers();
        $this->loadSession();
        $this->loadCache();
        $this->loadCookies();
        $this->loadEnvironment();
        $this->registeredClass();
        return $this->loadRoutes()->resolve($route, $params);
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    private function registerBaseAppSingleton()
    {
        $objs = ContainerAliasses::singleton();
        if (is_array($objs)) {
            foreach ($objs as $obj => $value) {
                $this->singleton($obj, $value);
            }
        }
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    private function registeredClass()
    {
        $objs = array_merge(ContainerAliasses::dataAccessLayerClass(), ContainerAliasses::bindedClass());
        if (is_array($objs)) {
            foreach ($objs as $obj => $value) {
                $this->bind($obj, $value);
            }
        }
    }

    private function handleCors()
    {
        $this->make(Cors::class)->handle();
        return $this;
    }
}