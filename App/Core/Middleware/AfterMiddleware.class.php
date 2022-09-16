<?php

declare(strict_types=1);

class AfterMiddleware implements MiddlewareInterface
{
    /**
     * @inheritdoc
     * @param object $middleware
     * @param Closure $next
     * @return void
     */
    public function middleware(Object $middleware, Closure $next)
    {
        return $next($middleware);
    }
}
