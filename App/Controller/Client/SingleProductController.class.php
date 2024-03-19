<?php

declare(strict_types=1);
class SingleProductController extends HttpController
{
    /**
     * IndexPage
     * ===================================================================.
     * @param array $data
     * @return void
     */
    protected function detailsPage(array $data = []) : ResponseHandler
    {
        $slug = array_pop($data);
        $this->pageTitle('Modile Phones - ' . $slug);
        $this->view()->addProperties(['name' => $slug]);
        $page = new SingleProductDecorator($this->page(), $slug);
        $page = new TopSalesDecorator($page);
        return $this->render('phones' . DS . 'details', $page->get()->display());
    }

    protected function singleClothesPage(array $data = []) : void
    {
        $slug = array_pop($data);
        $this->render('clothes' . DS . 'details', $this->showParams($slug, SingleClothesPage::class, 3, 'single_clophes'));
    }

    private function showParams(?string $slug, ?string $class = 'SinglePhonePage', int $brand = 2, ?string $cache = 'phones_products') : array
    {
        return $this->container($class, [
            'product' => $this->getSingleProduct($slug),
            'products' => $this->getProducts(brand: $brand, cache: $cache),
            'userCart' => $this->container(DisplayUserCart::class, [
                'userCart' => $this->getUserCart(),
            ]),
        ])->displayAll();
    }
}