<?php

declare(strict_types=1);
class NavBrandElement extends AbstractHTMLElement
{
    public function __construct(?string $template = null, ?array $params = [])
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $route = isset($this->params['route']) ? $this->params['route'] : '/';
        $template = str_replace('{{brandRoute}}', $route, $this->template);
        return ['navBrand', $template];
    }
}