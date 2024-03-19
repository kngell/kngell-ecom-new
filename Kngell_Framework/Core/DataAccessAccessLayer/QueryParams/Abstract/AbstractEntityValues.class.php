<?php

declare(strict_types=1);

abstract class AbstractEntityValues extends ValuesParameters
{
    abstract public function getValues(Entity $entity, ?int $enKey = null): ?string;

    protected function entityChecking(Entity $entity) : void
    {
        if (! $entity instanceof Entity) {
            throw new BadQueryArgumentException('You must provide an Entity object or a collection of entities');
        }
        if (! $entity->isPropertiesSet()) {
            throw new BadQueryArgumentException("There's no data to insert in the database");
        }
    }
}