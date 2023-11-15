<?php

declare(strict_types=1);

class CheckUserLoggedInMiddleware extends BeforeMiddleware
{
    public function __construct(private FilesSystemInterface $fs)
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
        $controller = $object->container('controller');
        $method = $object->container('method');
        $access = GrantAccess::getInstance()->hasAccess($controller, $method);
        if ($access === false && !AuthManager::isUserLoggedIn()) {
            $script = $this->fs->get(ASSET, 'js/client/main/open_login_modal.js');
            $script = '<script type="text/javascript" id="logWindowsScript">' .
                strval($script) .
                '</script>';
            $object->setArguments([$script]);
        }
        return $next($object);
    }
}