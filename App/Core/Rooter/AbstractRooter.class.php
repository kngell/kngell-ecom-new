<?php

declare(strict_types=1);

abstract class AbstractRooter
{
    protected RooterHelper $helper;
    protected ResponseHandler $response;
    protected RequestHandler $request;
    protected array $controllerProperties;
    protected array $arguments = [];
    protected array $params;
    protected string $controllerSuffix = 'Controller';
    protected ?string $namespace = null;

    public function __construct(?RooterHelper $helper = null, ?ResponseHandler $response = null, ?RequestHandler $request = null, array $controllerProperties = [])
    {
        $this->helper = $helper;
        $this->response = $response;
        $this->request = $request;
        $this->controllerProperties = $controllerProperties;
    }

    /**
     * Get the value of params.
     */
    public function getParams(): mixed
    {
        return $this->params;
    }

    /**
     * Set the value of params.
     */
    public function setParams($params): self
    {
        $this->params = $params;
        return $this;
    }

    protected function paramsNamespace(array $params = []) : string
    {
        if (is_array($params) && isset($params['namespace'])) {
            if (!str_ends_with($params['namespace'], DS)) {
                $params['namespace'] = $params['namespace'] . DS;
            }
            if (!preg_match('~^\p{Lu}~u', $params['namespace'])) {
                $params['namespace'] = ucfirst($params['namespace']);
            }
            return $params['namespace'];
        }
        return '';
    }

    protected function resolveControllerMethodDependencies(object $controllerObject, string $newAction): mixed
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
            return $reflectionMethod->invokeArgs($controllerObject, $dependencies);
        }
    }

    protected function createController(): string
    {
        $controllerName = $this->params['controller'] . $this->controllerSuffix;
        return StringUtil::studlyCaps($controllerName);
    }

    protected function createMethod(): string
    {
        $method = $this->params['method'];
        return StringUtil::camelCase($method);
    }

    protected function getUrl(?string $url = null) : string
    {
        $route = DS;
        $url = $url === null ? $this->request->getQuery()->get('url') : $url;
        if ($url != null) {
            if ($url == 'favicon.ico') {
                $this->arguments = [$url];
                return 'assets' . DS . 'getAsset';
            }
            $urlroute = explode(DS, filter_var(rtrim($url, DS)));
            if (isset($urlroute[0])) {
                if (in_array(strtolower($urlroute[0]), ['client', 'admin']) && count($urlroute) > 1) {
                    $this->namespace = ucfirst(strtolower($urlroute[0])) . DS;
                    unset($urlroute[0]);
                    $route = strtolower($urlroute[1]);
                    unset($urlroute[1]);
                } else {
                    $route = strtolower($urlroute[0]);
                    unset($urlroute[0]);
                }
                $urlroute = array_values($urlroute);
                if (isset($urlroute[0])) {
                    if ($route == 'assets') {
                        if ($urlroute[0] == 'img') {
                            unset($urlroute[0]);
                        }
                    } else {
                        $route = $route . DS . strtolower($urlroute[0]);
                        unset($urlroute[0]);
                    }
                }
            }
            $this->arguments = count($urlroute) > 0 ? $this->helper->formatUrlArguments(array_values($urlroute)) : [];
            return strtolower($route);
        }
        return $route;
    }
}
