<?php

declare(strict_types=1);
trait DisplayBackendPagesTrait
{
    protected function showProductsList(int|string $brand = null, ?string $cache = null) : array
    {
        return $this->container(ProductsListPage::class, [
            'products' => $this->getProducts(brand: $brand, cache: $cache),
            'productUnits' => $this->getUnits(),
            'shippingClass' => $this->getShippingClass(),
            'company' => $this->getCompany(),
            'warehouse' => $this->getWarehouse(),
            'categories' => $this->getCategories(),
        ])->displayAll();
    }

    protected function showEditProduct(ProductsManager $product) : array
    {
        return $this->container(EditProductPage::class, [
            'product' => $product,
        ])->displayAll();
    }
}