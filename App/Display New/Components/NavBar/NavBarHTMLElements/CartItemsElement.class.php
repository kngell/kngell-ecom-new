<?php

declare(strict_types=1);
class CartItemsElement extends AbstractHTMLElement
{
    private ?UserCartItemsForm $userCartForm;
    private CollectionInterface $userCart;

    public function __construct(?string $template = null, ?array $params = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $cartItems = isset($this->params['cartItems']) ? $this->params['cartItems'] : [];
        if (isset($cartItems)) {
            return ['itemsInCart', $cartItems];
        }
        return ['itemsInCart', ''];
    }

    private function whishlist() : string
    {
        $whislist = $this->userCart->filter(function ($item) {
            return $item->cartType === 'wishlist';
        });

        return '<a href="/cart#wishlist" class="px-3 border-right text-dark text-decoration-none" id="wishlist_items_count">Whishlist(' . ($whislist->count() > 0 ? $whislist->count() : 0) . ')</a>';
    }
}