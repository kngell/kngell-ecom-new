<?php

declare(strict_types=1);
class DisplayShoppingCartListener implements ListenerInterface
{
    private UserCartHTMLInterface $userCart;

    public function __construct(UserCartHTMLInterface $userCart)
    {
        $this->userCart = $userCart;
    }

    public function handle(EventsInterface $event): ?iterable
    {
        /** @var UserCartController */
        $object = $event->getObject();

        $page = new ShoppingCartDecorator($object);
        $msg = [
            'cartItems' => $this->userCart->getCartItems(),
            'whishlistItems' => $this->userCart->getWhishlistItems(),
        ];
        $msg = array_merge($msg, $page->get()->display());

        $object->getResponse()->jsonResponse([
            'result' => 'success',
            'msg' => $msg,
            'url' => $object->isInitialized('url') ? $object->getUrl() : null,
        ]);
        return null;
    }
}