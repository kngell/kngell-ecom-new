<?php

declare(strict_types=1);

class SelectPathMiddleware extends BeforeMiddleware
{
    public function __construct()
    {
    }

    /**
     * Show User Comment.
     *
     * @param object $middleware
     * @param Closure $next
     * @return mixed
     */
    public function middleware(object $object, Closure $next) : mixed
    {
        $route = $object->getRouteParams();
        if (str_contains($object->getFilePath(), 'Client' . DS)) {
            $object->setLayout('default');
            $object->frontComponents($object->displayLayout());
        } elseif (str_contains($object->getFilePath(), 'Admin' . DS)) {
            $object->setLayout('admin');
            $object->view()->siteTitle("K'nGELL Administration");
        }
        return $next($object);
    }
}