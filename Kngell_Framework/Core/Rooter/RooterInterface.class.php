<?php

declare(strict_types=1);
interface RooterInterface
{
    /**
     * Rooter.
     *
     * @param string $method
     * @param string $route
     * @param array|callable|null $routeHander
     * @return void
     */
    public function add(string $method, string $route, array|callable|null $routeHander): void;

    /**
     * resolve url by ceeating controller object and method.
     *
     * @param string|null $route
     * @param array|null $params
     * @return ResponseHandler
     */
    public function resolve(?string $route = null, ?array $params = []): ResponseHandler;
}