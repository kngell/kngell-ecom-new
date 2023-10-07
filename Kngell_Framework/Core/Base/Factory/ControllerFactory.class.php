<?php

declare(strict_types=1);

class ControllerFactory
{
    public function __construct(private string $controllerString, private array $routeParams, private array $controllerProperties)
    {
    }

    public function create() : Controller
    {
        $controllerObject = Application::diGet($this->controllerString, [
            'params' => $this->getControllerParams(),
        ]);
        if (!$controllerObject instanceof Controller) {
            throw new BadControllerExeption($this->controllerString . ' is not a valid Controller');
        }
        Application::getInstance()->bind('controller', fn () => $this->controllerString);
        return $controllerObject;
    }

    private function getControllerParams() : array
    {
        return array_merge($this->controllerProperties, [
            'viewPath' => $this->routeParams['namespace'] ?? '',
            'cachedFiles' => YamlFile::get('cache_files_list'),
            'routeParams' => $this->routeParams,
        ]);
    }
}