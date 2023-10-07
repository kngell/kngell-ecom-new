<?php

declare(strict_types=1);

class RooterFactory
{
    /** @var array */
    private array $routes;

    /**
     * Main constructor.
     */
    public function __construct(private RooterInterface $rooter, array $routes)
    {
        $this->routes = $routes;
    }

    public function create() : ?RooterInterface
    {
        if (empty($this->routes)) {
            throw new BaseNoValueException("There are one or more empty arguments. In order to continue, please ensure your <code>routes.yaml</code> has your defined routes and you are passing the correct variable ie 'QUERY_STRING'");
        }
        if (!$this->rooter instanceof RooterInterface) {
            throw new BaseUnexpectedValueException(get_class($this->rooter) . ' is not a valid rooter Object!');
        }
        return $this->addRoutes();
    }

    /**
     * Building Routes
     * =========================================================.
     * @return RooterInterface|null
     */
    public function addRoutes() : ?RooterInterface
    {
        if (isset($this->routes) && count($this->routes) > 0) {
            foreach ($this->routes as $httpMethod => $routes) {
                foreach ($routes as $route => $handler) {
                    if ($handler === null) {
                        $route = substr($route, 1);
                        $handler = [];
                    }
                    if (isset($route)) {
                        $this->rooter->add($httpMethod, $route, $handler);
                    }
                }
            }
            return $this->rooter;
        }
        return null;
    }
}