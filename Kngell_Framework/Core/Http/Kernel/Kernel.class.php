<?php

declare(strict_types=1);

class Kernel extends AbstractKernel
{
    /**
     * Main class constructor.
     *
     * @param Application $application
     */
    public function __construct()
    {
    }

    public function handle() : ResponseHandler
    {
        /* Attempting to run a single instance of the application */
        $app = Application::getInstance();
        try {
            $response = $app->setConst()->boot()->run();
        } catch (BaseResourceNotFoundException $e) {
            $app->make(ResponseHandler::class, [
                'content' => $e->getMessage(),
                'status' => HttpStatus::getName($e->getCode()),
            ])->prepare($app->make(RequestHandler::class));
            $response = $app->rooter()->resolve('error', [$e]);
        }
        return $response;
    }
}