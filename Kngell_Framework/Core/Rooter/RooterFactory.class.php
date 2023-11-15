<?php

declare(strict_types=1);

class RooterFactory
{
    /**
     * Main constructor.
     */
    public function __construct(
        private array $routes,
        private array $controllerProperties,
        private RoutesCollector $routesCollector,
        private UrlRoute $urlRoute,
    ) {
    }

    public function create() : ?RooterInterface
    {
        /** @var RoutesCollector */
        $routesCollector = $this->routesCollector->collectRoutes($this->routes);
        $rooter = Application::diGet(RooterInterface::class, [
            'urlRoute' => $this->urlRoute,
            'routesCollector' => $routesCollector,
            'controllerProperties' => $this->controllerProperties,
        ]);
        if (!$rooter instanceof RooterInterface) {
            throw new BaseUnexpectedValueException(get_class($rooter) . ' is not a valid rooter Object!');
        }
        return $rooter;
    }
}