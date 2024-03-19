<?php

declare(strict_types=1);

class ControllerFactory extends AbstractBaseFactory
{
    public function __construct(
        private string $controllerString,
        private array $routeParams,
        private array $controllerProperties,
    ) {
    }

    public function create() : AbstractController
    {
        $controllerObject = Application::diGet($this->controllerString);
        if (! $controllerObject instanceof AbstractController) {
            throw new BadControllerExeption($this->controllerString . ' is not a valid Controller');
        }
        return $this->initProperties($controllerObject, $this->controllerParams());
    }

    private function controllerParams() : array
    {
        return array_merge($this->controllerProperties, [
            // 'cachedFiles' => YamlFile::get('cache_files_list'),
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