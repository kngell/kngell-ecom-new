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
            $scriptContent = $this->fs->get(ASSET, 'js/client/main/open_login_modal.js');
            $script = '<script type="text/javascript">' .
                strval($scriptContent) .
                '</script>';
            echo $script;
            /** @var RooterInterface */
            $rooter = $object->container(RooterInterface::class);
            $rooter->resolve($this->route($object, $rooter));
            exit;
        }
        return $next($object);
    }

    private function route(object $object, RooterInterface $rooter) : string
    {
        $routeParams = $object->getRouteParams();
        $route = $object->getPreviousPage();
        if ($route !== null) {
            $previousRouteParams = $rooter->getMatchingRoutes(
                $route,
                $rooter->getRoutes()[$object->getRequest()->getMethod()]
            );
        }
        return match (true) {
            is_null($route) || empty($previousRouteParams) || empty(array_diff($routeParams, $previousRouteParams)) => 'client' . DS . 'restricted' . DS . 'login',
            default => $route
        };
    }
}