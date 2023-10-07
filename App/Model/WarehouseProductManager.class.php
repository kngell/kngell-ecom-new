<?php

declare(strict_types=1);
class WarehouseProductManager extends Model
{
    protected string $_colID = 'whp_id';
    protected string $_table = 'warehouse_product';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }
}