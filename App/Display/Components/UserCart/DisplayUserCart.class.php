<?php

declare(strict_types=1);

class DisplayUserCart extends AbstractDisplayUserCart implements DisplayPagesInterface
{
    use DisplayTraits;

    public function __construct(CollectionInterface|closure $userCart, UserCartItemsForm $userCartForm)
    {
        list($userCart) = $this->invoke([$userCart]);
        parent::__construct($userCart, $userCartForm);
    }

    public function displayAll() : array
    {
        return [
            'cartItems' => $this->userCartForm->createForm('#', $this->userCart),
            'whishlistItmes' => $this->whishlist(),
        ];
    }

    private function whishlist() : string
    {
        $whislist = $this->userCart->filter(function ($item) {
            return $item->cart_type === 'wishlist';
        });

        return '<a href="/cart#wishlist" class="px-3 border-right text-dark text-decoration-none" id="wishlist_items_count">Whishlist(' . ($whislist->count() > 0 ? $whislist->count() : 0) . ')</a>';
    }
}
