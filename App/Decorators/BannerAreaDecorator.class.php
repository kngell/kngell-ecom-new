<?php

declare(strict_types=1);

class BannerAreaDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => PhonesHomePageTemplatePaths::class,
        'sliders' => SlidersFromCache::class,
    ];
    protected ?SlidersFromCache $sliders;

    public function __construct(?AbstractController $controller = null)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $bannerAreaTemplate = $this->getTemplate('bannerAreaPath');
        $params = [
            'sliders' => $this->sliders != null ? $this->sliders->get()->filter(function ($sld) {
                return $sld->page_slider === 'index_phone';
            }) : null,
            'imgTemplate' => $this->getTemplate('bannerTemplatePath'),
        ];
        $banner = new BannerAreaHTMLElement($bannerAreaTemplate, $params);
        return $this->controller->get()->add($banner);
    }
}