<?php

declare(strict_types=1);

class SearchBoxElement extends AbstractHTMLElement
{
    public function __construct(?string $template = null, ?array $params = [])
    {
        // $template = $searchBoxForm->createForm('');
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        return ['searchBox', $this->template];
    }
}