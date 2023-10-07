<?php

declare(strict_types=1);

abstract class AbstractRooter
{
    protected ?UrlRoute $urlRoute;
    protected ?RequestHandler $request;
    protected array $routesCollector = [];
    protected array $controllerProperties;
    protected array $params = [];
    protected string $controllerSuffix = 'Controller';
    protected ?string $methodSuffix = '';

    public function __construct(?RequestHandler $request = null, ?UrlRoute $url = null, array $controllerProperties = [])
    {
        $this->request = $request;
        $this->urlRoute = $url;
        $this->controllerProperties = $controllerProperties;
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
        return $this->routesCollector;
    }

    protected function resolveWithException(string $route): closure|array
    {
        if (!$this->match($route, $this->routesCollector[strtolower($this->request->getHttpMethod())])) {
            $e = new RouterNoRoutesFound('Route ' . $route . ' does not match any valid route.', 404);
            //$e->setStatusCode(404);
            throw $e;
        }
        if (!class_exists($controller = $this->controllerString())) {
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

    protected function controllerObject(string $controllerString) : Controller
    {
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
        if (!isset($this->params['method'])) {
            throw new NoActionFoundException('the method is not defined');
        }
        return StringUtil::camelCase($this->params['method']);
    }
}