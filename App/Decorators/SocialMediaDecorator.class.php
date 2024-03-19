<?php

declare(strict_types=1);

class SocialMediaDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => SocialMediaPaths::class,
        'settings' => SettingsFromCache::class,
    ];
    protected ?SettingsFromCache $settings;

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $template = $this->getTemplate('socialMediaPath');
        $templateElt = $this->getTemplate('socialMediaLinkPath');
        $params = [
            'settings' => $this->settings->get(),
        ];
        $obj = new SocialMediaHTMLComponent($template);
        $obj->setLevel(1);
        $obj->add(new SocialMediaHTMLElement($templateElt, $params));
        return $this->controller->get()->add($obj);
    }
}