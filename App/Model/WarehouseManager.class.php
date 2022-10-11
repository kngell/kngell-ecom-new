<?php

declare(strict_types=1);
class WarehouseManager extends Model
{
    protected string $_colID = 'wh_id';
    protected string $_table = 'warehouse';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}