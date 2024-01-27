<?php

declare(strict_types=1);

class PDOConnexion extends AbstractConnection implements DatabaseConnexionInterface
{
    const REQUIRED_CONNECTION_KEYS = [
        'driver',
        'host',
        'db_name',
        'db_username',
        'db_user_password',
        'default_fetch',
    ];
    const DB_TYPE = 'pdo';

    public function __construct(DatabaseEnvironmentConfig $dbEnv)
    {
        parent::__construct($dbEnv, self::DB_TYPE);
    }

    /**
     * Open connection.
     *
     * @return self
     */
    public function open() : self
    {
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET UTF8mb4',
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => $this->credentials['default_fetch'],
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_NATURAL,
            PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING,
        ];
        if (! isset($this->con)) {
            try {
                $credentials = $this->parseCredentials();
                $this->con = new PDO($credentials[0], $credentials[1], $credentials[2], $options);
            } catch (PDOException $e) {
                throw new DatabaseConnexionExceptions($e->getMessage(), (int) $e->getCode());
            }
        }
        return $this;
    }

    /**
     * Set the value of credentials.
     *
     * @param  array  $credentials
     *
     * @return  self
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Get the value of con.
     *
     * @return  bool|PDO
     */
    public function getConnexion() : bool|PDO
    {
        return isset($this->con) ? $this->con : false;
    }

    protected function parseCredentials(): array
    {
        $dsn = sprintf(
            '%s:host=%s;%s;dbname=%s;charset=%s',
            $this->credentials['driver'],
            $this->credentials['host'],
            $this->credentials['driver'],
            isset($this->credentials['port']) ? 'port=' . $this->credentials['port'] : '',
            $this->credentials['charset'],
        );
        return [$dsn, $this->credentials['db_username'], $this->credentials['db_user_password']];
    }
}
