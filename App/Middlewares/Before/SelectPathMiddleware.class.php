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
        if (str_contains($object->getviewPath(), 'Client' . DS)) {
            $object->setLayout('default');
            $object->frontComponents($object->displayLayout());
        } elseif (str_contains($object->getViewPath(), 'Admin' . DS)) {
            $object->setLayout('admin');
            $object->view()->siteTitle("K'nGELL Administration");
            $object->setSelect2Field(YamlFile::get('select2Field')['admin']);
        } else {
            $object->setLayout('');
        }
        return $next($object);
    }
}