<?php

declare(strict_types=1);

class DataMapperFactory
{
    /**
     * Main constructor
     * ================================================================.
     *@return void
     */
    public function __construct()
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
        $dataMapperObject = Application::diget(DataMapperInterface::class);
        if (! $dataMapperObject instanceof DataMapperInterface) {
            throw new DataMapperExceptions(DataMapperInterface::class . ' is not a valid database connexion Object!');
        }
        return $dataMapperObject;
    }
}
