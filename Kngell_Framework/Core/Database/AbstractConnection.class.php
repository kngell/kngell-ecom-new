<?php

declare(strict_types=1);
abstract class AbstractConnection
{
    const REQUIRED_CONNECTION_KEYS = [];
    const DB_TYPE = '';
    /**
     * @var array
     */
    protected array $credentials;
    /**
     * @var PDO
     */
    protected PDO $con;

    public function __construct(DatabaseEnvironmentConfig $dbEnv, string $type)
    {
        $this->credentials = $dbEnv->getCredentials($type);
        if (! $this->credentialsHaveRequiredKeys()) {
            throw new InvalidArgumentException(sprintf('Database credentials are not mapped correctly. Required keys are: %s', implode(', ', static::REQUIRED_CONNECTION_KEYS)));
        }
    }

    public function getConnection(): PDO
    {
        return $this->con;
    }

    public function close():void
    {
        $this->con = null;
    }

    public function beginTransaction() : bool
    {
        return $this->con->beginTransaction();
    }

    public function exec(string $sql) : int|false
    {
        return $this->con->exec($sql);
    }

    public function inTransaction() : bool
    {
        return $this->con->inTransaction();
    }

    public function rollBack() : bool
    {
        return $this->con->rollBack();
    }

    public function commit() : bool
    {
        return $this->con->commit();
    }

    /**
     * Get the value of credentials.
     *
     * @return  array
     */
    public function getCredentials() : array
    {
        return $this->credentials;
    }

    abstract protected function parseCredentials() : array;

    protected function credentialsHaveRequiredKeys() : bool
    {
        $matches = array_intersect(static::REQUIRED_CONNECTION_KEYS, array_keys($this->credentials));
        return count($matches) === count(static::REQUIRED_CONNECTION_KEYS);
    }
}
