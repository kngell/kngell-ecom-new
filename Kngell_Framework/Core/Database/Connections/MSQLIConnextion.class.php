<?php

declare(strict_types=1);
class MYSQLIConnection extends AbstractConnection implements DatabaseConnexionInterface
{
    const REQUIRED_CONNECTION_KEYS = [
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch',
    ];
    const DB_TYPE = 'mysqli';

    public function __construct(DatabaseEnvironmentConfig $dbEnv)
    {
        parent::__construct($dbEnv, self::DB_TYPE);
    }

    public function open(): self
    {
        if (! isset($this->con)) {
            $driver = new mysqli_driver;
            $driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;
            $credentials = $this->parseCredentials();
            try {
                $this->con = new mysqli(...$credentials);
            } catch (Throwable $e) {
                throw new DatabaseConnexionExceptions($e->getMessage(), (int) $e->getCode());
            }
        }
        return $this;
    }

    /**
     * Get the value of con.
     *
     * @return  bool|mysqli
     */
    public function getConnexion() : bool|mysqli
    {
        return isset($this->con) ? $this->con : false;
    }

    protected function parseCredentials(): array
    {
        return [
            $this->credentials['host'],
            $this->credentials['db_username'],
            $this->credentials['db_user_password'],
            $this->credentials['db_name'],
        ];
    }
}
