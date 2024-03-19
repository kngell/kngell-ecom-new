<?php

declare(strict_types=1);

class SpecialPriceDecorator extends AbstractPageDecorator
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
        $brandButton = $this->categoriesButton();
        $specialTemplate = $this->getTemplate('specialPricePath');
        $productTemplate = $this->getTemplate('specialPriceTemplate');
        $specialTemplate = str_replace('{{brandButton}}', ! empty($brandButton) ? implode('', $brandButton) : '', $specialTemplate);
        $productTemplate = str_replace('{{singleProductTemplate}}', $this->getTemplate('productTemplatePath'), $productTemplate);
        $params = [
            'money' => $this->money,
            'userCart' => $this->userCart,
            'products' => $this->products,
            'frm' => $this->frm,
            'productTemplate' => $productTemplate,
            'productformTemplate' => $this->getTemplate('productFormPath'),
        ];
        $sectionObj = new SpecialPriceHTMLComponent($specialTemplate);
        $sectionObj->setLevel(1);
        $sectionObj->add(new SpecialPriceHTMLElement($specialTemplate, $params));
        return $this->controller->get()->add($sectionObj);
    }

    private function categoriesButton() :  array
    {
        $brandButton = [];
        if ($this->products->count() > 0) {
            $brands = array_unique(array_map(function ($prod) {
                return $prod->categorie;
            }, $this->products->all()));
            sort($brands);
            if (isset($brands)) {
                $brandButton = array_map(function ($brand) {
                    return sprintf('<button class="btn" data-filter=".%s">%s</button>', $brand, $brand);
                }, $brands);
            }
        }
        return $brandButton;
    }
}