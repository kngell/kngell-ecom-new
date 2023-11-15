<?php

declare(strict_types=1);

class ProductsListPaths implements TemplatePathsInterface
{
    private string $viewPath = VIEW . 'admin' . DS . 'pages' . DS . 'products' . DS;
    private string $templatePath = APP . 'Display' . DS . 'Admin' . DS . 'Products' . DS . 'Templates' . DS;
    private string $filesPath = FILES . 'Template' . DS . 'Base' . DS . 'Media' . DS;

    public function Paths(): CollectionInterface
    {
        return new Collection(array_merge($this->templatesPath(), $this->viewPath()));
    }

    private function templatesPath() : array
    {
        return [
            'productsListPaths' => $this->templatePath . 'productListTemplate.php',
            'productsfrmPaths' => $this->templatePath . 'productListFrmTemplayte.php',
            'dragandDroppath' => $this->filesPath . 'dragandDropTemplate.php',
            'productInfoPath' => $this->templatePath . 'productInfosTemplate.php',
            'productMediaPath' => $this->templatePath . 'productMediaTemplate.php',
            'productPricingPath' => $this->templatePath . 'productPricingTemplate.php',
            'productInventoryPath' => $this->templatePath . 'productInventoryTemplate.php',
            'productShippingPath' => $this->templatePath . 'productShippingTemplate.php',
            'productVariantsPath' => $this->templatePath . 'productVariantsTemplate.php',
            'productOrgaPath' => $this->templatePath . 'productOrganizationTemplate.php',
            'productCatPath' => $this->templatePath . 'productCategoriesTemplate.php',
            'productTagsPath' => $this->templatePath . 'productTagsTemplate.php',
            'productformPath' => $this->templatePath . 'productFormTemplate.php',
            'categoriePath' => $this->templatePath . 'productCategoriesTemplate.php',
            'categorieItemPath' => $this->templatePath . 'productCategorieItemTemplate.php',
        ];
    }

    private function viewPath() : array
    {
        return [
            'menuPath' => $this->viewPath . '_transaction_menu.php',
        ];
    }
}
