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

    public function addUserItem(UserCartItems $userCart) : self
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME) && $this->entity instanceof CartEntity) {
            $itemId = $this->entity->getItemId();
            $cartElt = $userCart->get()->getObjectWithValue('itemId', $itemId);
            if (empty($cartElt)) {
                return $this->assign([
                    'userId' => $this->cookie->get(VISITOR_COOKIE_NAME),
                ])->save();
            }
            $this->setCount(1); //Item already exist in cart
        } else {
            /** @var VisitorsManager */
            $m = $this->factory->create(VisitorsManager::class);
            $m->addNewVisitor();
            return $this->addUserItem($userCart);
        }
        return $this;
    }

    public function getUserCart() : CollectionInterface
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)) {
            $this->query()->select()
                ->leftJoin('products', 'pdtId', 'title', 'regularPrice', 'chargeTax', 'media', 'color', 'size')
                ->on('cart|itemId', 'products|pdtId')
                ->leftJoin('product_categorie', 'catId')
                ->on('products|pdtId', 'product_categorie|pdtId')
                ->leftJoin('categories', 'categorie')
                ->on('product_categorie|catId', 'categories|catId')
                ->where(['cart|userId' => $this->cookie->get(VISITOR_COOKIE_NAME)])
                ->groupBy('products|pdtId')
                ->return('object');
            return new Collection($this->getAll()->get_results());
        }
        return new Collection([]);
    }

    public function deleteItem(?Entity $entity = null): ?Model
    {
        if (is_null($entity)) {
            /** @var CartEntity */
            $en = $this->entity;
            $this->query()->delete()->where('cartId', $en->getCartId(), 'itemId', $en->getItemId())->go();
        }
        return $this->delete();
    }

    public function updateQty(UserCartItems $userCart) : self
    {
        if ($this->cookie->exists(VISITOR_COOKIE_NAME)
        && $this->entity instanceof CartEntity) {
            foreach ($userCart->get()->all() as $item) {
                if ($item->itemId === $this->entity->getItemId()) {
                    $item->itemQty = $this->entity->getItemQty();
                    $this->assign(['cartId' => $item->cartId, 'userId' => $this->cookie->get(VISITOR_COOKIE_NAME)]);
                }
            }
        }

        return $this;
    }
}