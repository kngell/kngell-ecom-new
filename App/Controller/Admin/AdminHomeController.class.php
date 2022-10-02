<?php

declare(strict_types=1);

class AdminHomeController extends Controller
{
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }

    protected function indexPage(array $args = []) : void
    {
        $this->setLayout('adminTest');
        $this->pageTitle('Admin - Ecommerce Management');
        $this->view()->addProperties(['name' => 'Admin Home Page']);
        $this->render('pages' . DS . 'index');
    }
}