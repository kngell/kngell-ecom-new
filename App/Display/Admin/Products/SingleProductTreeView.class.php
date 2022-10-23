<?php

declare(strict_types=1);

class SingleProductTreeView implements DisplayPagesInterface
{
    protected ProductsManager $product;

    public function __construct(ProductsManager $product)
    {
        $this->product = $product;
    }

    public function displayAll(): mixed
    {
    }
}