<?php

declare(strict_types=1);

class AdminHomePagePaths implements TemplatePathsInterface
{
    private string $viewPath = VIEW . 'admin' . DS . 'pages' . DS;
    private string $templatePath = APP . 'Display' . DS . 'Admin' . DS . 'Home' . DS . 'Templates' . DS;

    public function Paths(): CollectionInterface
    {
        return new Collection(array_merge($this->templatesPath(), $this->viewPath()));
    }

    private function templatesPath() : array
    {
        return [
            'userNamePath' => $this->templatePath . 'adminUserNameTemplate.php',
        ];
    }

    private function viewPath() : array
    {
        return [
            'menuPath' => $this->viewPath . '_transaction_menu.php',
        ];
    }
}
