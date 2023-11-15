<?php

declare(strict_types=1);

class RoutesCollector
{
    private array $routes = [];

    public function __construct()
    {
    }

    public function collectRoutes(array $routes) : self
    {
        if (empty($routes)) {
            throw new BaseNoValueException("There are one or more empty arguments. In order to continue, please ensure your <code>routes.yaml</code> has your defined routes and you are passing the correct variable ie 'QUERY_STRING'");
        }
        if (isset($routes) && count($routes) > 0) {
            foreach ($routes as $httpMethod => $routeCateory) {
                foreach ($routeCateory as $route => $handler) {
                    if ($handler === null) {
                        $route = substr($route, 1);
                        $handler = [];
                    }
                    if (isset($route)) {
                        $this->add($httpMethod, $route, $handler);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Get the value of routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    private function add(string $method, string $route, array $routeHander): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-_]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$method][$route] = $routeHander;
    }
}