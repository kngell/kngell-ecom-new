<?php

declare(strict_types=1);

class SingleProductDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => PhonesHomePageTemplatePaths::class,
        'products' => ProductsFromCache::class,
        'userCart' => UserCartHTMLElement::class,
        'frm' => FormBuilder::class,
        'money' => MoneyManager::class,
    ];
    protected ?object $products;
    protected ?FormBuilder $frm;
    protected ?UserCartHTMLInterface $userCart;
    protected ?MoneyManager $money;

    public function __construct(?AbstractController $controller, mixed $slug = null)
    {
        parent::__construct($controller);

        Application::getInstance()->bindParameters(abstract:ProductsFromCache::class, args:[
            'method' => 'getSingleProduct',
            'args' => $slug,
            'cacheFileName' => StringUtil::studlyCaps($slug),
        ], argName:'options');
        $this->initProperties();
        Application::getInstance()->remove(abstract:ProductsFromCache::class);
    }

    public function get(): AbstractHTMLComponent
    {
        $template = $this->getTemplate('productDetailsPath');
        $singleProd = new SingleProductHTMLComponent($template);
        $singleProd->setLevel(1);
        $params = [
            'products' => $this->products,
            'money' => $this->money,
            'userCart' => $this->userCart,
            'frm' => $this->frm,
            'imgGalleryTemplate' => $this->getTemplate('imgGalleryTemplate'),
        ];
        $singleProd->add(new SingleProductHTMLElement($template, $params));

        return  $this->controller->get()->add($singleProd);
    }
}