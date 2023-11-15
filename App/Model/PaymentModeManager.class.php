<?php

declare(strict_types=1);
class PaymentModeManager extends Model
{
    protected string $_colID = 'pm_id';
    protected string $_table = 'payment_mode';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function all() : CollectionInterface
    {
        $this->table()->return('object');
        return new Collection($this->getAll()->get_results());
    }
}