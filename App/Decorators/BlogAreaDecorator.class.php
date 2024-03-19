<?php

declare(strict_types=1);

class BlogAreaDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => PhonesHomePageTemplatePaths::class,
    ];

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get(): AbstractHTMLComponent
    {
        $blogTemplate = $this->getTemplate('blogAreaPath');
        $params = [
        ];

        $blogArea = new BlogHTMLElement($blogTemplate, $params);
        return $this->controller->get()->add($blogArea);
    }
}