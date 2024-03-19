<?php

declare(strict_types=1);

class BannerAddsDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => PhonesHomePageTemplatePaths::class,
    ];

    public function __construct(?AbstractController $controller = null)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $bannerAreaTemplate = $this->getTemplate('bannerAddPath');
        $params = [
        ];
        $banner = new BannerAddsHTMLElement($bannerAreaTemplate, $params);
        return $this->controller->get()->add($banner);
    }
}