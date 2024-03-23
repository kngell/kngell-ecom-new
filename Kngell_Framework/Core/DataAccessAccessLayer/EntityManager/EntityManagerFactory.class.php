<?php

declare(strict_types=1);

class EntityManagerFactory
{
    public function __construct(private DataMapperInterface $dataMapper, private QueryParamsInterface $query, private CrudFactory $crudFactory)
    {
    }

    public function create() : EntityManagerInterface
    {
        $em = new EntityManager($this->crudFactory->create($this->dataMapper, $this->query));
        if (! $em instanceof EntityManagerInterface) {
            throw new EntityManagerExceptions(get_class($em) . ' is not a valid entityManager object!');
        }
        return $em;
    }
}