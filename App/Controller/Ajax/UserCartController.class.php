<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserCartController extends AjaxController
{
    protected string $url;
    private UserCartItems $userCart;

    public function __construct(UserCartItems $userCart)
    {
        $this->userCart = $userCart;
    }

    public function add(array $args = [], ?string $link = null) : void
    {
        /** @var CartManager */
        $model = $this->model($this->userCart)->assign($this->isValidRequest());
        if ($model->addUserItem($this->userCart)->count() === 1) {
            $link != null ? $this->setUrl($link) : '';
            Application::getInstance()->bind(UserCartHTMLInterface::class, UserCartHTMLElement::class);
            $this->dispatcher()->dispatch(new UserCartChangeEvent($this, '', ['displayUserCart']));
        }
        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Something goes wrong!')]);
    }

    public function deleteItem(array $args = [], ?string $process = 'delete') : void
    {
        $userIncomeData = $this->isValidRequest();
        $selectedItem = $this->selectedItem($userIncomeData);
        if ($selectedItem->count() === 1) {
            $resp = $this->model($this->userCart)->assign($userIncomeData)
                ->assign([
                    'cartId' => $selectedItem->pop()->cartId,
                ])->$process();
            if ($resp->count() === 1) {
                Application::getInstance()->bind(UserCartHTMLInterface::class, UserCartHTMLElement::class);
                $this->dispatcher()->dispatch(new ShoppingCartChangeEvent($this, ''));
            }
        }
        $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Something goes wrong!')]);
    }

    public function saveForLater(array $args = []) : void
    {
        // /** @var CartManager */
        // $model = $this->model(CartManager::class)->assign($this->isValidRequest());
        // $shopping_cart = $this->selectedItem($model);
        $this->deleteItem($args, 'save');

        // $userIncomeData = $this->isValidRequest();
        // $selectedItem = $this->selectedItem($userIncomeData);
        // if ($selectedItem->count() === 1) {
        //     $resp = $this->model($this->userCart)->assign([
        //         'cartId' => $selectedItem->pop()->cartId,
        //         'itemId' => $userIncomeData['itemId'],
        //     ])->save();
        //     if ($resp->count() === 1) {
        //         Application::getInstance()->bind(UserCartHTMLInterface::class, UserCartHTMLElement::class);
        //         $this->dispatcher()->dispatch(new ShoppingCartChangeEvent($this, '', [
        //             'displayUserCart',
        //             'displayShoppingCart',
        //         ]));
        //     }
        // }
        // $this->jsonResponse(['result' => 'error', 'msg' => $this->helper->showMessage('warning', 'Something goes wrong!')]);

        // if ($shopping_cart->count() === 1) {
        //     $item = $shopping_cart->pop();
        //     $model->assign(['cartId' => $item->cartId]);
        //     if ($model->save()->count() === 1) {
        //         $this->dispatcher->dispatch(new UserCartChangeEvent($this, '', [
        //             'displayUserCart',
        //             'displayShoppingCart',
        //         ]));
        //     }
        // }
    }

    /**
     * Set the value of url.
     *
     * @return  self
     */
    public function setUrl(string $url) : self
    {
        $this->url = $url;
        return $this;
    }

    public function qtyChange(array $args = []) : void
    {
        /** @var CartManager */
        $model = $this->model(CartManager::class)->assign($this->isValidRequest());
        $model = $this->updateQty($model);
        if ($model->save()) {
            $this->dispatcher->dispatch(new UserCartChangeEvent($this, '', ['shoppingCart']));
            // $this->cache->delete($this->cachedFiles['user_cart']);
            // $this->jsonResponse(['result' => 'success', 'msg' => $this->shoppingCart()['shoppingCart']]);
        }
        $this->jsonResponse(['result' => 'success', 'msg' => ['nbItems' => 0]]);
    }

    public function buy(array $args) : void
    {
        $this->add($args, '/cart');
    }

    /**
     * Get the value of userCart.
     */
    public function getUserCart(): UserCartItems
    {
        return $this->userCart;
    }

    /**
     * Set the value of userCart.
     */
    public function setUserCart(UserCartItems $userCart): self
    {
        $this->userCart = $userCart;

        return $this;
    }

    /**
     * Get the value of url.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    private function selectedItem(array $userIncomeData) : CollectionInterface
    {
        if (! isset($userIncomeData['itemId'])) {
            throw new BadRequestException('No Data found in Cart. Please Try again');
        }
        $items = $this->userCart->get();
        $item = (int) $userIncomeData['itemId'];
        return $items->filter(function ($sc) use ($item) {
            return $sc->itemId === $item;
        });
    }

    private function updateQty(CartManager $m) : CartManager
    {
        /** @var CollectionInterface */
        $shopping_cart = $this->getUserCart();
        /** @var CartEntity */
        $en = $m->getEntity();
        foreach ($shopping_cart as $item) {
            if ($item->itemId === $en->getItemId()) {
                $item->itemQty = $en->getItemQty();
                $m->assign(['cartId' => $item->cartId]);
            }
        }

        return $m;
    }
}