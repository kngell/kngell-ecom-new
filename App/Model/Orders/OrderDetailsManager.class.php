<?php

declare(strict_types=1);

class OrderDetailsManager extends Model
{
    protected $_colID = 'od_id';
    protected $_table = 'order_details';
    protected $_colIndex = 'od_order_id';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function getOrderDetails(array $keys) : CollectionInterface
    {
        $this->table()
            ->leftJoin('products', ['title', 'short_descr', 'media'])
            ->on(['od_product_id|order_details', 'pdtId|products'])
            ->where(['od_order_id|in' => [$keys, 'order_details']])
            ->return('object');

        return new Collection($this->getAll()->get_results());
    }
}
