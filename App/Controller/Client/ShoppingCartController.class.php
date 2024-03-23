<?php

declare(strict_types=1);

class ShoppingCartController extends HttpController
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * Display sinle Product.
     *
     * @param array $data
     * @return ResponseHandler
     */
    protected function indexPage(array $data = []) : ResponseHandler
    {
        $page = new ShoppingCartDecorator($this->page());
        $page = new NewProductsDecorator($page);
        $this->pageTitle('Shopping Cart');
        return $this->render('shoppingCart' . DS . 'shoppingCart', $page->get()->display());
    }
}