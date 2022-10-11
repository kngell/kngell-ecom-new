<?php

declare(strict_types=1);

class AdminPagesController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    protected function indexPage(array $args = []) : void
    {
        $this->pageTitle('Admin - Ecommerce Management');
        $this->view()->addProperties(['name' => 'Admin Home Page']);
        $this->render('pages' . DS . 'index');
    }

    protected function allProductsPage(array $params = [])
    {
        $this->pageTitle('Admin - All Products');
        $this->view()->addProperties(['name' => 'All Products']);
        $this->render('products' . DS . 'allProducts', $this->showAdminProducts(
            brand:null,
            cache: $this->cachedFiles['products_list']
        ));
    }

    protected function allBrandsPage(array $params = [])
    {
        $this->pageTitle('Admin - All Brands');
        $this->view()->addProperties(['name' => 'All Brands']);
        $this->render('brands' . DS . 'allBrands');
    }

    protected function allCategoriesPage(array $params = [])
    {
        $this->pageTitle('Admin - All Categories');
        $this->view()->addProperties(['name' => 'All Categories']);
        $this->render('brands' . DS . 'allCategories');
    }

    protected function allUsersPage(array $params = [])
    {
        $this->pageTitle('Admin - All Users');
        $this->view()->addProperties(['name' => 'All Users']);
        $this->render('users' . DS . 'allUsers');
    }
}