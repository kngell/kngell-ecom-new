<?php

declare(strict_types=1);

class TopSalesDecorator extends AbstractPageDecorator
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
    protected string $section = 'topSales';

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $template = $this->getTemplate('topSalesPath');
        $productTemplate = $this->getTemplate('topSalesTemplatePath');
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate('productTemplatePath'), $productTemplate);
        $params = [
            'money' => $this->money,
            'userCart' => $this->userCart,
            'products' => $this->products,
            'frm' => $this->frm,
            'productTemplate' => $productTemplate,
            'productformTemplate' => $this->getTemplate('productFormPath'),
        ];
        $sectionObj = new TopSalesHTMLComponent($template);
        $sectionObj->setLevel(1);
        $sectionObj->add(new TopSalesHTMLElement($template, $params));
        return $this->controller->get()->add($sectionObj);
    }
}