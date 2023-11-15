<?php

declare(strict_types=1);

interface EntityInterface
{
    public function getColId(string $withDocComment = 'id', bool $entityProp = false) :  string;
}