<?php

declare(strict_types=1);
interface RooterInterface
{
    /**
     * Add Route
     * ======================================================.
     *
     * @param string $route
     * @param string $method
     * @param array $params
     * @return void
     */
    public function add(string $method, string $route, array $params) : void;

    /**
     * Resolve
     * ======================================================.
     */
    public function resolve() : self;

    public function getMatchingRoutes(string $url, array $routes) : array;

    public function getRoutes() : array;

    public function getParams(): mixed;
}