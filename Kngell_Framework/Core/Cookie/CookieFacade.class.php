<?php

declare(strict_types=1);

class CookieFacade
{
    /** @var string - the namespace reference to the cookie store type */
    protected CookieStoreInterface $store;

    /**
     * Main cookie facade class which pipes the properties to the method arguments.
     * Which also defines the default cookie store.
     *
     * @param array $cookieEnvironmentArray - expecting a cookie.yaml configuration file
     * @param string $store - optional defaults to nativeCookieStore
     * @return void
     */
    public function __construct(array $cookieEnvironmentArray, ?CookieConfig $cookieConfig = null, ?GlobalVariablesInterface $gv = null)
    {
        $cookieArray = array_merge($cookieConfig->baseConfig(), $cookieEnvironmentArray);
        $this->store = Application::diget(CookieStoreInterface::class, [
            'cookieEnvironment' => Application::diGet(CookieEnvironment::class, [
                'cookieConfig' => $cookieArray,
            ]),
            'gv' => $gv,
        ]);
    }

    /**
     * Create an instance of the cookie factory and inject all the required
     * dependencies ie. the cookie store object and the cookie environment
     * configuration.
     *
     * @return CookieInterface
     */
    public function initialize(): CookieInterface
    {
        return Application::diget(CookieFactory::class)->create($this->store);
    }
}