<?php

declare(strict_types=1);
class LoginAttemptsManager extends Model
{
    protected string $_colID = 'laId';
    protected string $_table = 'login_attempts';
    protected string $_colIndex = 'userId';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}