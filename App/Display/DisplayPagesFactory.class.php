<?php

declare(strict_types=1);

class DisplayPagesFactory
{
    public function __construct()
    {
    }

    public function create(string $page, array $args = []): mixed
    {
        return match (true) {
            $page == 'homePage' => $this->homePage($args),
            $page == 'singleProduct' => $this->singleProduct($args),
            default => throw new BadMethodCallException('Impossible d\'aficher cette page', 505),
        };
    }

    private function homePage() : string
    {
        return $this->container(DisplayPhonesInterface::class, [
            'products' => $this->getProducts(brand: $brand, cache: $cache),
            'userCart' => $this->container(DisplayUserCart::class, [
                'userCart' => $this->getUserCart(),
            ]),
            'slider' => $this->getSliders(),
        ])->displayAll();
    }
}