<?php

declare(strict_types=1);

class DataMapperFactory
{
    /**
     * Main constructor
     * ================================================================.
     *@return void
     */
    public function __construct(private DataMapperInterface $dataMapperObject)
    {
    }

    /**
     * Create method
     * ============================================================.
     * @param string $databaseConnexionObject
     * @param string $dataMapperEnvConfigObject
     *@return DataMapperInterface
     */
    public function create() : DataMapperInterface
    {
        if (! $this->dataMapperObject instanceof DataMapperInterface) {
            throw new DataMapperExceptions(DataMapperInterface::class . ' is not a valid database connexion Object!');
        }
        return $this->dataMapperObject;
    }
}