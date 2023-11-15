<?php

declare(strict_types=1);
class CartManager extends Model
{
    protected string $_colID = 'cartId';
    protected string $_table = 'cart';
    protected string $_colIndex = 'userId';

    public function __construct()
    {
        parent::__construct($this->_table, $this->_colID);
    }

    public function addUserItem() : self
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $this->table(null, ['COUNT|cartId|nbItems'])->where([
                'userId' => $this->cookie->get(VISITOR_COOKIE_NAME),
                'itemId' => $this->entity->getFieldValue('itemId'),
            ])->return('current');
            $userCart = new Collection(current($this->getAll()->get_results()));
            if ($userCart->offsetGet('nbItems') == 0) {
                return $this->assign([
                    'userId' => $this->cookie->get(VISITOR_COOKIE_NAME),
                ])->save();
            }
        } else {
            Application::diGet(VisitorsManager::class)->addNewVisitor();
        }

        return $this;
    }

    public function getUserCart() : CollectionInterface
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $this->table()
                ->leftJoin('products', ['pdt_id', 'title', 'regular_price', 'charge_tax', 'media', 'color', 'size'])
                ->leftJoin('product_categorie', ['cat_id'])
                ->leftJoin('categories', ['categorie'])
                ->on(['itemId', 'pdt_id'], ['pdt_id', 'pdt_id'], ['cat_id', 'cat_id'])
                ->where(['userId' => $this->cookie->get(VISITOR_COOKIE_NAME)])
                ->groupBy(['pdt_id' => 'products'])
                ->return('object');

            return new Collection($this->getAll()->get_results());
        }

        return new Collection([]);
    }
}
