<?php

declare(strict_types=1);

class NewProductsDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => PhonesHomePageTemplatePaths::class,
        'products' => ProductsFromCache::class,
        'userCart' => UserCartHTMLElement::class,
        'frm' => FormBuilder::class,
        'money' => MoneyManager::class,
    ];
    protected ?CollectionInterface $products;
    protected ?FormBuilder $frm;
    protected ?UserCartHTMLInterface $userCart;
    protected ?MoneyManager $money;

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $template = $this->getTemplate('newProductPath');
        $productTemplate = $this->getTemplate('newProductTemplate');
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate('productTemplatePath'), $productTemplate);
        $params = [
            'money' => $this->money,
            'userCart' => $this->userCart,
            'products' => $this->products,
            'frm' => $this->frm,
            'productTemplate' => $productTemplate,
        ];
        $obj = new NewProductsHTMLComponent($template);
        $obj->setLevel(1);
        $obj->add(new NewProductsHTMLElement($template, $params));
        return $this->controller->get()->add($obj);
    }
}