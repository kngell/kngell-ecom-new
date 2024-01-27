<?php

declare(strict_types=1);

interface DatabaseConnexionInterface
{
    /**
     * DataBase open
     * -------------------------------------------------.
     * @return self
     */
    public function open(): self;

    /**
     * Data Base close
     * -------------------------------------------------.
     * @return void
     */
    public function close():void;

    public function beginTransaction() : bool;

    public function exec(string $sql) : int|false;

    public function inTransaction() : bool;

    public function rollBack() : bool;

    public function commit() : bool;

    public function getConnection() : PDO;
}
