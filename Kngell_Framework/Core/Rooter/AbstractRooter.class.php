<?php

declare(strict_types=1);

abstract class AbstractRooter
{
    protected ?UrlRoute $urlRoute;
    protected ?RoutesCollector $routesCollector = null;
    protected ResponseHandler $response;
    protected RequestHandler $request;
    protected array $controllerProperties;
    protected array $params = [];
    protected string $controllerSuffix = 'Controller';
    protected ?string $methodSuffix = '';

    public function __construct(UrlRoute $urlRoute, RoutesCollector $routesCollector, array $controllerProperties, ResponseHandler $response, RequestHandler $request)
    {
        $this->urlRoute = $urlRoute;
        $this->routesCollector = $routesCollector;
        $this->controllerProperties = $controllerProperties;
        $this->response = $response;
        $this->request = $request;
    }

    public function getMatchingRoutes(string $url, array $routes) : array
    {
        if ($this->match($url, $routes)) {
            return $this->params;
        }
        return [];
    }

    public function getRoutes() : array
    {
        return $this->routesCollector->getRoutes();
    }

    protected function resolveWithException(string $route): closure|array
    {
        if (! $this->match($route, $this->routesCollector->getRoutes()[strtolower($this->urlRoute->getRequest()->getHttpMethod())])) {
            throw new RouterNoRoutesFound('Route ' . $route . ' does not match any valid route.', 404);
        }
        if (! class_exists($controller = $this->controllerString())) {
            throw new RouterBadFunctionCallException('Class ' . $controller . ' does not exists.', 404);
        }
        if ($controller instanceof Closure) {
            return $controller;
        } else {
            return [$controller, $this->controllerMethod()];
        }
    }

    protected function match(string $url, array $routes) : bool
    {
        foreach ($routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if (is_string($key)) {
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    protected function controllerObject(string $controllerString) : AbstractController
    {
        if (! class_exists($controllerString)) {
            throw new BadControllerExeption('Controller ' . $controllerString . ' does not exists.', 404);
        }
        return Container::getInstance()->make(ControllerFactory::class, [
            'controllerString' => $controllerString,
            'controllerProperties' => $this->controllerProperties,
            'routeParams' => $this->params,
        ])->create();
    }

    protected function resolveControllerMethodDependencies(object $controllerObject, string $newAction): array
    {
        $newAction = $newAction . $this->methodSuffix;
        $reflectionMethod = new ReflectionMethod($controllerObject, $newAction);
        $reflectionMethod->setAccessible(true);
        if ($reflectionMethod) {
            $dependencies = [];
            foreach ($reflectionMethod->getParameters() as $param) {
                $newAction = Application::diGet(YamlFile::get('providers')[$param->getName()]);
                if (isset($newAction)) {
                    $dependencies[] = $newAction;
                } elseif ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                }
            }
            $reflectionMethod->setAccessible(false);
            //return $reflectionMethod->invokeArgs($controllerObject, $dependencies);
            return $dependencies;
        }
    }

    protected function controllerString(): string
    {
        return StringUtil::studlyCaps($this->params['controller'] . $this->controllerSuffix);
    }

    protected function controllerMethod(): string
    {
        if (! isset($this->params['method'])) {
            throw new NoActionFoundException('the method is not defined');
        }
        return StringUtil::camelCase($this->params['method']);
    }
}