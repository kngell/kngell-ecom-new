<?php

declare(strict_types=1);

class CrudFactory
{
    public function __construct()
    {
    }

    public function create(DataMapperInterface $dataMapper, QueryParamsInterface $query) : CrudInterface
    {
        $crud = new Crud($dataMapper, $query);
        if (! $crud instanceof CrudInterface) {
            throw new CrudExceptions(get_class($crud) . ' is not a valid CrudInterface object!');
        }
        return $crud;
    }
}