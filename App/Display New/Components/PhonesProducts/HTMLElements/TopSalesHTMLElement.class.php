<?php

declare(strict_types=1);
class TopSalesHTMLElement extends AbstractProductsHTMLElement
{
    public function __construct(?string $template = null, ?array $params = [])
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $productTemplate = isset($this->params['productTemplate']) ? $this->params['productTemplate'] : '';
        return['topSalesHTML', $this->iteratedOutput($this->template, $productTemplate)];
    }
}