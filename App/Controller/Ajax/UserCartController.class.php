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
            $this->dispatcher()->dispatch(new UserCartChangeEvent($this));
        }
    }

    public function deleteItem(array $args = [], ?string $process = 'deleteItem') : void
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
                $this->dispatcher()->dispatch(new ShoppingCartChangeEvent($this));
            }
        }
    }

    public function saveForLater(array $args = []) : void
    {
        $this->deleteItem($args, 'save');
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
        $model = $this->model($this->userCart)->assign($this->isValidRequest());
        $resp = $model->updateQty($this->userCart);
        if ($resp->save()) {
            Application::getInstance()->bind(UserCartHTMLInterface::class, UserCartHTMLElement::class);
            $this->dispatcher()->dispatch(new ShoppingCartChangeEvent($this));
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
}