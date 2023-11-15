<?php

declare(strict_types=1);
interface RooterInterface
{
    /**
     * resolve url by ceeating controller object and method.
     *
     * @param string|null $route
     * @param array|null $params
     * @return ResponseHandler
     */
    public function resolve(?string $route = null, ?array $params = []): ResponseHandler;
}