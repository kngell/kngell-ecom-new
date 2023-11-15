<?php

declare(strict_types=1);

class AutologinManager extends Model
{
    protected string $_colID = 'alId';
    protected string $_table = 'autologin';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}