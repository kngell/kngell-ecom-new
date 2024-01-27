<?php

declare(strict_types=1);
class DatabaseEnvironmentConfig
{
    /**
     * --------------------------------------------
     * Cedentials.
     * @var array
     */
    private array $credentials = [];

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * ---------------------------------------------
     * Get user data base credentials.
     *@param string $driver
     * @return bool-array
     */
    public function getCredentials(string $type) : bool|array
    {
        $this->isCredentialsValid($type);
        if (array_key_exists($type, $this->credentials)) {
            return $this->credentials[$type];
        }
        return false;
    }

    /**
     * ----------------------------------------------
     * Check for Valid credentials.
     *@param string driver
     * @return array
     */
    private function isCredentialsValid(string $driver)
    {
        if (empty($driver) && ! is_string($driver)) {
            throw new DataMapperInvalidArgumentException('Invalid Argument! This is missing or invalid Data type');
        }
        if (! is_array($this->credentials)) {
            throw new DataMapperInvalidArgumentException('Invalid Credentials!');
        }
        // if (! in_array('driver', array_keys($this->credentials))) {
        //     throw new DataMapperInvalidArgumentException('Invalid or unsupported driver');
        // }
    }
}
