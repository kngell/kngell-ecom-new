<?php

declare(strict_types=1);
class urlCheckParametersMiddleware extends BeforeMiddleware
{
    public function __construct()
    {
    }

    /**
     * check for valid url.
     *
     * @param object $middleware
     * @param Closure $next
     * @return mixed
     */
    public function middleware(object $object, Closure $next) : mixed
    {
        if (in_array($object::class, ['HomeController'])) {
            /** @var CommentsManager */
            $model = $object->model(CommentsManager::class);
            $template = $object->getComment()->showAllComments($model->getComments(), $model->maxComments());
            $object->setCommentsArg($template);
        }
        return $next($object);
    }
}