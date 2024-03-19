<?php

declare(strict_types=1);

interface DataFromCacheInterface
{
    public function get() : null|CollectionInterface|stdClass;
}