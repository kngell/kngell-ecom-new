<?php

declare(strict_types=1);
class ModelFactory extends AbstractBaseFactory
{
    private array $properties = [];

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function create(string $modelString) : AbstractModel
    {
        $modelObject = new $modelString;
        if (! $modelObject instanceof AbstractModel) {
            throw new BadControllerExeption($modelString . ' is not a valid Model');
        }
        Application::getInstance()->bind($modelString, fn () => $modelObject);
        return $this->initProperties($modelObject, $this->properties);
    }
}