<?php

declare(strict_types=1);
class BannerAreaHTMLElement extends AbstractHTMLElement
{
    private string $banner = 'bannerArea';

    public function __construct(?string $template = null, ?array $params = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        return [$this->banner => $this->bannerAreaSection()];
    }

    private function bannerAreaSection() : string
    {
        $html = '';
        $imgTemplate = isset($this->params['imgTemplate']) ? $this->params['imgTemplate'] : '';
        $sliders = isset($this->params['sliders']) ? $this->params['sliders'] : '';
        if ($sliders->count() === 1) {
            $sliders = $sliders->pop();
            $medias = unserialize($sliders->media);
            foreach ($medias as $image) {
                $imgTemplate = str_replace('{{image}}', ImageManager::asset_img($image), $imgTemplate);
                $imgTemplate = str_replace('{{title}}', $sliders->slider_title, $imgTemplate);
                $html .= $imgTemplate;
            }
        }

        return str_replace('{{bannerTemplate}}', $html, $this->template);
    }
}