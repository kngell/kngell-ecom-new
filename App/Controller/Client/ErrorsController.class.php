<?php

declare(strict_types=1);
class ErrorsController extends HttpController
{
    public function indexPage($data) : ResponseHandler
    {
        // $this->setLayout('default');
        $this->pageTitle('Errors');
        $this->view()->addProperties(['name' => 'Errors']);
        return $this->render('errors' . DS . '_errors', $data);
    }

    public function userPage($data)
    {
        $this->pageTitle('Errors');
        $this->view()->addProperties(['name' => 'Errors']);
        $this->render('errors' . DS . '404', $data);
    }
}