<?php

declare(strict_types=1);

class CheckoutController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = []) : ResponseHandler
    {
        $this->pageTitle('Checkout Page');
        return $this->render('checkout' . DS . 'checkout', array_merge($this->displayCheckoutPage(), ['jv_script_from_php' => $data[0] ?? '']));
    }
}