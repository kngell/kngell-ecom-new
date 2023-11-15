<?php

declare(strict_types=1);
interface ApplicationInterface
{
    public function handle(?string $route = null, array $params = []) : ResponseHandler;

    public function make(string $abstract, array $args = []): mixed;
}