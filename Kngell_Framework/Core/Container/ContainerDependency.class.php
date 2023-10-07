<?php

declare(strict_types=1);
class ContainerDependency
{
    public function __construct(private ContainerDependency $dependency)
    {
    }
}