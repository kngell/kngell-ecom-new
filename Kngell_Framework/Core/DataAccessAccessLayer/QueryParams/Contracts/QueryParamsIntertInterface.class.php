<?php

declare(strict_types=1);

interface QueryParamsInsertInterface
{
    public function into(string $tbl, ...$params) : self;

    public function fields(...$fields) : self;

    public function values(...$values) : self;

    public function exec() : CollectionInterface;
}