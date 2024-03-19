<?php

declare(strict_types=1);

class LoadPageMiddleware extends BeforeMiddleware
{
    private HTMLComponent $page;

    public function __construct()
    {
    }

    public function loadPage()
    {
        $page = new HTMLComponent();
    }
}