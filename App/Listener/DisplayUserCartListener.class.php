<?php

declare(strict_types=1);
class DisplayUserCartListener implements ListenerInterface
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
        $object->getResponse()->jsonResponse([
            'result' => 'success',
            'msg' => [
                'cartItems' => $this->userCart->getCartItems(),
                'whishlistItems' => $this->userCart->getWhishlistItems(),
            ],
            'url' => $object->isInitialized('url') ? $object->getUrl() : null,
        ]);
        return null;
    }
}