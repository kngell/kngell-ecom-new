<?php

declare(strict_types=1);

class Rooter extends AbstractRooter implements RooterInterface
{
    public function __construct(UrlRoute $urlRoute, RoutesCollector $routesCollector, array $controllerProperties, ResponseHandler $response, RequestHandler $request)
    {
        parent::__construct($urlRoute, $routesCollector, $controllerProperties, $response, $request);
    }

    /**
     * Resolve route.
     *
     * @param string|null $route
     * @param array|null $params
     * @return ResponseHandler
     */
    public function resolve(?string $route = null, ?array $params = []): ResponseHandler
    {
        $route = $this->getRoute($route);
        list($controllerString, $method) = $this->resolveWithException($route);
        if ($controllerString instanceof closure) {
            return $this->response->setContent($controllerString->__invoke())->prepare($this->request);
        }
        $controllerObject = $this->controllerObject($controllerString);
        if (preg_match('/method$/i', $method) == 0) {
            if (YamlFile::get('app')['system']['use_resolvable_method'] === true) {
                return $this->resolveControllerMethodDependencies($controllerObject, $method);
            }
            if (is_callable([$controllerObject, $method], true, $callableName)) {
                Application::getInstance()->bind('method', fn () => $method);
                Application::getInstance()->bind('controller', fn () => $controllerString);
                return $controllerObject->$method(array_merge($params, $this->urlRoute->geturlParams()));
            } else {
                throw new NoActionFoundException("Method $method in controller $controllerString cannot be called", 405);
            }
        } else {
            throw new NoActionFoundException("Method $method in controller $controllerString cannot be called directly - remove the Action suffix to call this method", 405);
        }
    }

    private function getRoute(?string $route): string
    {
        if (null === $route) {
            return $this->urlRoute->getRoute();
        } else {
            return $this->urlRoute->handle($route)->getRoute();
        }
    }
}