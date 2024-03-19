<?php

declare(strict_types=1);

abstract class AbstractPageDecorator extends AbstractController
{
    protected const PROPERTIES = [];
    protected ?TemplatePathsInterface $paths;
    protected ?AbstractController $controller;

    public function __construct(?AbstractController $controller)
    {
        $this->controller = $controller;
    }

    abstract public function get() : AbstractHTMLComponent;

    protected function initProperties() : void
    {
        foreach (static::PROPERTIES as $key => $class) {
            if (! isset($this->{$key}) && property_exists($this, $key)) {
                $this->{$key} = $key == 'products' ? (Application::diGet($class))->get() : Application::diGet($class);
            }
        }
    }

    protected function getTemplate(string $path) : string
    {
        $this->isFileexists($this->paths->paths()->offsetGet($path));
        return file_get_contents($this->paths->paths()->offsetGet($path));
    }

    protected function isFileexists(string $file) : bool
    {
        if (! file_exists($file)) {
            throw new BaseException('File does not exist!', 1);
        }
        return true;
    }
}