<?php

declare(strict_types=1);
class HttpController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

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

    protected function render(string $viewName, array $context = []) : ?ResponseHandler
    {
        $this->throwViewException();
        if ($this->view === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        $content = $this->view()->render($viewName, array_merge($this->frontEndComponents, $context));
        return $this->response->setContent($content);
    }

    protected function before() : void
    {
        $this->createView();
        // $this->saveViewPage();
        Application::diGet(Middleware::class)->middlewares(middlewares: $this->callBeforeMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function after() : void
    {
        Application::diGet(Middleware::class)->middlewares(middlewares: $this->callAfterMiddlewares(), contructorArgs:[])
            ->middleware($this, function ($object) {
                return $object;
            });
    }

    protected function defineCoreMiddeware(): array
    {
        return [
            'Error404' => Error404::class,
            // 'ShowCommentsMiddlewares' => ShowCommentsMiddlewares::class,
            // 'CheckUserLoggedInMiddleware' => CheckUserLoggedInMiddleware::class,
            // 'SelectPathMiddleware' => SelectPathMiddleware::class,

        ];
    }

    protected function callBeforeMiddlewares(): array
    {
        return array_merge($this->defineCoreMiddeware(), $this->callBeforeMiddlewares);
    }

    protected function callAfterMiddlewares(): array
    {
        return $this->callAfterMiddlewares;
    }

    protected function pageTitle(?string $page = null)
    {
        $this->isValidView();
        return $this->view->pageTitle($page);
    }

    protected function registerMiddleware(BaseMiddleWare $middleware) : void
    {
        $this->middlewares[] = $middleware;
    }

    protected function registerBeforeMiddleware(array $middlewares = []) : void
    {
        foreach ($middlewares as $name => $middleware) {
            $this->callBeforeMiddlewares[$name] = $middleware;
        }
    }

    protected function throwViewException(): void
    {
        if (null === $this->view) {
            throw new BaseLogicException('You can not use the render method if the build in template engine is not available.');
        }
    }

    protected function isValidView() : bool
    {
        if ($this->view === null) {
            throw new BaseLogicException('You cannot use the render method if the View is not available !');
        }
        return true;
    }
}