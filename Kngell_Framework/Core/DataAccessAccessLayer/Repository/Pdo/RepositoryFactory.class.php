<?php

declare(strict_types=1);

class RepositoryFactory
{
    /**
     * Main constructor
     * ==================================================================.
     */
    public function __construct(private EntityManagerFactory $emFactory)
    {
    }

    public function create() : RepositoryInterface
    {
        $repositoryObject = new Repository($this->emFactory->create());
        if (! $repositoryObject instanceof RepositoryInterface) {
            throw new BaseUnexpectedValueException(get_class($repositoryObject) . ' is not a valid repository Object!');
        }
        return $repositoryObject;
    }
}