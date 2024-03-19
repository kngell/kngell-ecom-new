<?php

declare(strict_types=1);

class EntityManagerFactory
{
    public function __construct(
        // private DataMapperFactory $dataMapperFactory,
        private DataMapperInterface $dataMapper,
        private CrudFactory $crudFactory,
    ) {
    }

    /**
     * Create EntityManager
     * =====================================================================.
     *
     * @param string $tableSchma
     * @param string $tableShameID
     * @param array $options
     * @return EntityManagerInterface
     */
    public function create(
        string $tableSchema,
        string $tableSchemaID,
        array $options,
        QueryBuilderInterface $queryBuilder
    ) : EntityManagerInterface {
        // $dataMapper = $this->dataMapperFactory->create();
        $crud = $this->crudFactory->create(
            $this->dataMapper,
            $queryBuilder,
            $tableSchema,
            $tableSchemaID,
            $options,
        );
        $em = Application::diget(EntityManagerInterface::class, [
            'crud' => $crud,
        ]);
        if (! $em instanceof EntityManagerInterface) {
            throw new EntityManagerExceptions(get_class($em) . ' is not a valid entityManager object!');
        }

        return $em;
    }
}