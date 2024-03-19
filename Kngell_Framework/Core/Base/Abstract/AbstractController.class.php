<?php

declare(strict_types=1);

abstract class AbstractController
{
    protected View $view;
    protected RequestHandler $request;
    protected ResponseHandler $response;
    protected CacheInterface $cache;
    protected Token $token;
    protected ?EventDispatcherInterface $dispatcher;
    protected ?ControllerHelper $helper;
    protected array $arguments = [];
    protected array $routeParams = [];
    protected array $frontEndComponents = [];
    protected array $callBeforeMiddlewares = [];
    protected array $callAfterMiddlewares = [];
    protected array $middlewares = [];
    protected string $viewPath;

    public function __construct()
    {
    }

    /**
     * Get the value of response.
     */
    public function getResponse(): ResponseHandler
    {
        return $this->response;
    }

    /**
     * Set the value of response.
     */
    public function setResponse(ResponseHandler $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function isInitialized(string $field) : bool
    {
        $rp = CustomReflector::getInstance()->reflectionObj($this)->getProperty($field);
        if ($rp->isInitialized($this)) {
            return true;
        }
        return false;
    }

    abstract public function get() : ?AbstractHTMLComponent;

    protected function view() : View
    {
        $this->createView();
        return $this->view;
    }

    protected function createView() : void
    {
        if (! isset($this->view)) {
            $this->view = Application::diGet(View::class, [
                'viewAry' => [
                    'token' => $this->token,
                    'viewPath' => $this->viewPath,
                ],
            ]);
        }
    }

    protected function dispatcher() : ?EventDispatcherInterface
    {
        if (! isset($this->dispatcher)) {
            return Application::diGet(DispatcherFactory::class)->create();
        }
    }
}