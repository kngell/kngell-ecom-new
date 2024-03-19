<?php

declare(strict_types=1);
class HomeController extends HttpController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * IndexPage
     * =========================================.
     * @param array $data
     * @return void
     */
    protected function indexPage(array $data = []) : ResponseHandler
    {
        if (! empty($data)) {
            throw new InvalidArgumentException('Page invalide', 404);
        }
        $model = $this->model(SettingsManager::class);
        $q = $model->testNewQuery();
        $query = $q->getQuery()->proceed();
        dd($query);
        // Preparing HTML element to display on the home page.
        $page = new BannerAreaDecorator($this->page()); // Banner Area
        $page = new BannerAddsDecorator($page); // Banner Adds
        $page = new TopSalesDecorator($page); // top sales section
        $page = new BlogAreaDecorator($page); // Blog Area
        $page = new SpecialPriceDecorator($page); // Special Price Section
        $page = new NewProductsDecorator($page); //NewProducts

        $this->pageTitle('Modile Phones - Best Apparel Online Store');
        $this->view()->addProperties(['name' => 'Home Page']);

        return $this->render('phones' . DS . 'index', $page->get()->display());
    }
    // protected function vetementsPage(array $args = []) : void
    // {
    //     $this->pageTitle('Clothing - Best Clothes ever seen');
    //     $this->view()->addProperties(['name' => 'Home Clothes Page']);
    //     $this->render('clothes' . DS . 'clothing', $this->displayClothes(brand: 3, cache: 'clothes_products'));
    // }

    // protected function boutiqueVetementsPage(array $args = []) : void
    // {
    //     $this->pageTitle('Clothing - Shop Page');
    //     $this->view()->addProperties(['name' => 'Shop Page']);
    //     $this->render('clothes' . DS . 'shop', $this->displayClothesShop(brand: 3, cache: 'clothes_products'));
    // }

    // protected function todoPage(array $agrs = [])
    // {
    //     $this->view()->addProperties(['name' => 'Todo List']);
    //     $this->render('todoList' . DS . 'todo');
    // }

    // /**
    //  * IndexPage
    //  * ===================================================================.
    //  * @param array $data
    //  * @return void
    //  */
    // protected function libPage(array $data = []) : void
    // {
    //     $this->setLayout('clothes');
    //     $this->pageTitle('Clothing - Best Aparels Online Store');
    //     $this->view()->addProperties(['name' => 'Home Page']);
    //     $this->render('home' . DS . 'lib');
    // }
}