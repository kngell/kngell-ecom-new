<?php

declare(strict_types=1);

abstract class Controller_old extends AbstractController_old
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /** @inheritDoc */
    public function __call(string $name, array $arguments) : ResponseHandler
    {
        if (is_string($name) && $name !== '') {
            $method = $name . 'Page';
            if (method_exists($this, $method)) {
                if ($this->before() !== false) {
                    $args = empty($this->arguments) ? $arguments : array_merge($arguments[0], [$this->arguments]);
                    $response = call_user_func_array([$this, $method], $args);
                    $this->after();
                    return $response->prepare($this->request);
                }
            } else {
                throw new BaseBadMethodCallException("Method {$method} does not exists.", 405);
            }
        } else {
            throw new Exception;
        }
    }

    public function render(string $viewName, array $context = []) : ?ResponseHandler
    {
        $this->throwViewException();
        if ($this->view_instance === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        $content = $this->view()->render($viewName, array_merge($this->frontEndComponents, $context));
        return $this->response->setContent($content);
    }

    public function model(string $modelString) : Object
    {
        return $this->container(ModelFactory::class)->create($modelString);
    }

    public function createView() : void
    {
        if (! isset($this->view_instance)) {
            $this->view_instance = $this->container(View::class, [
                'viewAry' => [
                    'token' => $this->token,
                    'viewPath' => $this->viewPath,
                ],
            ]);
        }
    }

    public function isInitialized(string $field) : bool
    {
        $rp = $this->reflectionInstance()->getProperty($field);
        if ($rp->isInitialized($this)) {
            return true;
        }
        return false;
    }

    public function jsonResponse(array $resp) : void
    {
        $this->response->jsonResponse($resp);
    }

    protected function before() : void
    {
        $this->createView();
        $this->saveViewPage();
        $this->container(Middleware::class)->middlewares(middlewares: $this->callBeforeMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function after() : void
    {
        $this->container(Middleware::class)->middlewares(middlewares: $this->callAfterMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function brand() : int
    {
        switch (self::class) {
            case 'ClothingController':
                return 3;
                break;

            default:
                return 2;
                break;
        }
    }
}