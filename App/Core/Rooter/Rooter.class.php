<?php

declare(strict_types=1);

class Rooter extends AbstractRooter implements RooterInterface
{
    private array $routes = [];
    private ContainerInterface $container;

    public function __construct(?RooterHelper $helper, ?ResponseHandler $response, ?RequestHandler $request, array $controllerProperties)
    {
        parent::__construct($helper, $response, $request, $controllerProperties);
    }

    /** @inheritDoc */
    public function add(string $method, string $route, array $params): void
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-_]+)', $route); //adding underscore
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$method][$route] = $params;
    }

    /** @inheritDoc */
    public function resolve(?string $url = null, array $params = []): self
    {
        $url = $this->getUrl($url);
        list($controllerString, $method) = $this->resolveWithException($url);
        $controllerObject = $this->controllerObject($controllerString, $method);
        if (preg_match('/method$/i', $method) == 0) {
            if (YamlFile::get('app')['system']['use_resolvable_method'] === true) {
                $this->resolveControllerMethodDependencies($controllerObject, $method);
            } elseif (is_callable([$controllerObject, $method], true, $callableName)) {
                $controllerObject->$method(array_merge($this->arguments, $params));
            } else {
                throw new NoActionFoundException("Method $method in controller $controllerString cannot be called");
            }
        } else {
            throw new NoActionFoundException("Method $method in controller $controllerString cannot be called directly - remove the Action suffix to call this method");
        }
        return $this;
    }

    public function match(string $url, array $routes) : bool
    {
        foreach ($routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $params['namespace'] = $this->getNamespace($params, $matches);
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

    public function resolveWithException(string $url): array
    {
        if (!$this->match($url, $this->routes[$this->request->getMethod()])) {
            http_response_code(404);
            throw new RouterNoRoutesFound('Route ' . $url . ' does not match any valid route.', 404);
        }
        if (!class_exists($controller = $this->createController())) {
            throw new RouterBadFunctionCallException('Class ' . $controller . ' does not exists.');
        }
        return [$controller, $this->createMethod()];
    }

    public function getMatchingRoutes(string $url, array $routes) : array
    {
        if ($this->match($url, $routes)) {
            return $this->params;
        }
        return [];
    }

    public function controllerObject(string $controllerString, string $method) : Controller
    {
        return $this->container->make(ControllerFactory::class, [
            'controllerString' => $controllerString,
            'method' => $method,
            'controllerProperties' => $this->controllerProperties,
            'routeParams' => $this->params,
        ])->create();
    }

    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * Get the namespace for the controller class. the namespace difined within the route parameters
     * only if it was added.
     *
     * @return string
     */
    public function getNamespace(array $params = [], array $matches = []) : string
    {
        if ($this->namespace !== null) {
            return $this->namespace;
        }
        if ($this->namespace === null && !array_key_exists('namespace', $params)) {
            return 'Client' . DS;
        }
        if (!array_key_exists('namespace', $params)) {
            if (is_array($matches) && isset($matches[0])) {
                $parts = explode(DS, $matches[0]);
                if (count($parts) > 2) {
                    return ucfirst($parts[0]) . DS;
                }
            }
            return '';
        }
        return $this->paramsNamespace($params);
    }
}