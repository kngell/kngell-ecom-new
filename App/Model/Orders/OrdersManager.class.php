<?php

declare(strict_types=1);

class OrdersManager extends Model
{
    protected $_colID = 'ord_id';
    protected $_table = 'orders';
    protected $_colIndex = 'ord_userId';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function all() : CollectionInterface
    {
        /** @var OrdersEntity */
        $en = $this->getEntity();
        if ($en->isInitialized('ord_userId')) {
            $this->table()
                ->leftJoin('users', ['firstName', 'lastName'])
                ->join('order_details')
                ->leftJoin('products', ['title', 'short_descr', 'media'])
                ->leftJoin('order_status', ['status'])
                ->on(['ord_userId|orders', 'userId|users'], ['ord_id|orders', 'od_order_id|order_details'], ['od_product_id|order_details', 'pdtId|products'], ['ord_status|orders', 'os_id|order_status'])
                ->where([$this->_colIndex => $en->getOrdUserId()])
                ->return('object');

            return new Collection($this->getAll()->get_results());
        }

        return new Collection();
    }

    public function AllWithSearchAndPagin(array $params = []) : CollectionInterface
    {
        /** @var OrdersEntity */
        $en = $this->getEntity();
        if ($en->isInitialized('ord_userId')) {
            $this->table()
                ->leftJoin('users', ['firstName', 'lastName'])
                ->leftJoin('order_status', ['status'])
                ->on(['ord_userId|orders', 'userId|users'], ['ord_status|orders', 'os_id|order_status'])
                ->where([$this->_colIndex => $en->getOrdUserId()])
                ->parameters(['limit' => 3, 'offset' => 0])
                ->return('object');
            $r = $this->getAllWithSearchAndPagin($params)->get_results();
            $orders = $r['results'];
            unset($r['results']);
            $keys = array_unique(array_column($orders, 'ord_id'));

            return new Collection([
                'orders' => new Collection($orders),
                'order_details' => $this->container(OrderDetailsManager::class)->getOrderDetails($keys),
                'params' => array_merge($r, $params),
            ]);
        }

        return new Collection();
    }
}
