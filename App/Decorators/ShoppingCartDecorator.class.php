<?php

declare(strict_types=1);

class ShoppingCartDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => ShoppingCartPaths::class,
        'userCart' => UserCartHTMLElement::class,
        'money' => MoneyManager::class,
        'frm' => FormBuilder::class,
    ];
    protected ?UserCartHTMLInterface $userCart;
    protected ?FormBuilder $frm;
    protected ?MoneyManager $money;

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get() : AbstractHTMLComponent
    {
        $obj = new HTMLBaseComponent('');
        $obj->setLevel(1);
        $shoppingCarttemplate = $this->getTemplate('shoppingCartPath');
        $shoppingItemsCollectiontemplate = $this->getTemplate('shoppingItemCollectionPath');
        $subTotalTemplate = $this->getTemplate('shoppingCartsubtotalPath');

        $cart = new ShoppingCartHTMLComponent($shoppingCarttemplate);
        $cart->setLevel($obj->getLevel() + 1);

        $cartParams = [
            'userCart' => $this->userCart,
            'money' => $this->money,
            'frm' => $this->frm,
            'shoppingItemPath' => $this->getTemplate('shoppingItemPath'),
            'shoppingQtyform' => $this->getTemplate('shoppingQtyformPath'),
            'shoppingDelForm' => $this->getTemplate('shoppingDelFormPath'),
            'cartTaxTemplate' => $this->getTemplate('cartTaxTemplate'),
            'proceedTobuyForm' => $this->getTemplate('proceedTobuyFormPath'),
            'emptyTemplate' => $this->getTemplate('emptycartPath'),
        ];
        $cart->add(new ShoppingCartHTMLElement($shoppingItemsCollectiontemplate, $cartParams));
        $cart->add(new ShoppingCartSubTotalHTMLElement($subTotalTemplate, $cartParams));
        $obj->add($cart);

        $whishlist_template = $this->getTemplate('whishlistPath');
        $whishlistCollectionTemplate = $this->getTemplate('whishlistCollectionPath');
        $whislist = new WhislistCartHTMLComponent($whishlist_template);
        $whislist->setLevel($obj->getLevel() + 1);
        $whislistParams = [
            'userCart' => $this->userCart,
            'money' => $this->money,
            'frm' => $this->frm,
            'shoppingDelForm' => $this->getTemplate('shoppingDelFormPath'),
            'wishlistItemsPath' => $this->getTemplate('whishlistItemPath'),

        ];
        $whislist->add(new WhislistHTMLElement($whishlistCollectionTemplate, $whislistParams));
        $obj->add($whislist);

        return $this->controller->get()->add($obj);
    }
}