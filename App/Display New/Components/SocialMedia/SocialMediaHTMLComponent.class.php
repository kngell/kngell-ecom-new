<?php

declare(strict_types=1);
class SocialMediaHTMLComponent extends AbstractHTMLComponent
{
    private string $html;
    private string $section = 'socialsMedia';

    public function __construct(?string $template = null)
    {
        parent::__construct($template);
    }

    public function display(): array
    {
        $html = '';
        $childs = $this->children->all();
        foreach ($childs as $child) {
            [$property,$html] = $child->display();
            if (property_exists($this, $property)) {
                $this->{$property} = $html;
            }
        }
        $html = str_replace('{{socialLink}}', $this->html, $this->template);
        return [$this->section => $html];
    }
}