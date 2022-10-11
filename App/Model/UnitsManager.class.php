<?php

declare(strict_types=1);
class UnitsManager extends Model
{
    protected string $_colID = 'un_id';
    protected string $_table = 'units';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}