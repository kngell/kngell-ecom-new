<?php

declare(strict_types=1);

class RouteCollector
{
    protected array $routes = [];

    public function add(string $method, string $route, array $routeHander): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-_]+)', $route); //adding underscore
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$method][$route] = $routeHander;
    }

    /**
     * Get the value of routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}