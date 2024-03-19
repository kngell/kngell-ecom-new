<?php

declare(strict_types=1);
class BlogHTMLElement extends AbstractHTMLElement
{
    private string $blog = 'blogArea';

    public function __construct(?string $template = null, ?array $params = null, ?string $banner = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        return [$this->blog => $this->displayBlogArea()];
    }

    private function displayBlogArea() : string
    {
        $blogTemplate = str_replace('{{blog1}}', ImageManager::asset_img('blog' . DS . 'blog1.jpg'), $this->template);
        $blogTemplate = str_replace('{{blog2}}', ImageManager::asset_img('blog' . DS . 'blog2.jpg'), $blogTemplate);
        return str_replace('{{blog3}}', ImageManager::asset_img('blog' . DS . 'blog3.jpg'), $blogTemplate);
    }
}