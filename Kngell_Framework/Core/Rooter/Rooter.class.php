<?php

declare(strict_types=1);

class Rooter extends AbstractRooter implements RooterInterface
{
    public function __construct(?RequestHandler $request, ?UrlRoute $url, array $controllerProperties)
    {
        parent::__construct($request, $url, $controllerProperties);
    }

    /** @inheritDoc */
    public function add(string $httpMethod, string $route, array|callable|null $routeHander = null): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-_]+)', $route); //adding underscore
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routesCollector[$httpMethod][$route] = $routeHander;
    }

    /** @inheritDoc */
    public function resolve(?string $route = null, ?array $params = []): ResponseHandler
    {
        $route = $route ?? $this->urlRoute->handle($this->request)->getRoute();
        list($controllerString, $method) = $this->resolveWithException($route);
        if ($controllerString instanceof closure) {
            return Container::getInstance()->make(ResponseHandler::class, [
                'content' => $controllerString->__invoke(),
            ]);
        }
        $controllerObject = $this->controllerObject($controllerString);
        if (preg_match('/method$/i', $method) == 0) {
            if (YamlFile::get('app')['system']['use_resolvable_method'] === true) {
                return $this->resolveControllerMethodDependencies($controllerObject, $method);
            }
            if (is_callable([$controllerObject, $method], true, $callableName)) {
                Container::getInstance()->bind('method', fn () => $method);
                // return [
                //     $controllerObject, $method,
                //     array_merge($this->url->geturlParams(), $params),
                // ];
                return $controllerObject->$method($params);
            } else {
                throw new NoActionFoundException("Method $method in controller $controllerString cannot be called", 405);
            }
        } else {
            throw new NoActionFoundException("Method $method in controller $controllerString cannot be called directly - remove the Action suffix to call this method", 405);
        }
    }
}