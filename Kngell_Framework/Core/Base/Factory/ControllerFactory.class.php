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
        return $controllerObject;
    }

    private function getControllerParams() : array
    {
        return array_merge($this->controllerProperties, [
            'cachedFiles' => YamlFile::get('cache_files_list'),
            'routeParams' => $this->routeParams,
            'viewPath' => $this->viewPath(),
        ]);
    }

    private function viewPath() : ?string
    {
        $class = CustomReflector::getInstance()->reflectionInstance($this->controllerString);
        return basename(dirname($class->getFileName())) . DS;
    }
}