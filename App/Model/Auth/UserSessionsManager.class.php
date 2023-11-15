<?php

declare(strict_types=1);
class UserSessionsManager extends Model
{
    protected string $_colID = 'usId';
    protected string $_table = 'user_sessions';
    protected string $_colIndex = 'userId';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getEntity() : UserSessionsEntity
    {
        return $this->entity;
    }

    public function test()
    {
    }
}