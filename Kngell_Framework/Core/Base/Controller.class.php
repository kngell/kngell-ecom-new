<?php

declare(strict_types=1);

class Controller extends AbstractController
{
    protected AbstractHTMLComponent $htmlComp;

    public function page() : AbstractController
    {
        $page = new NavBarDecorator($this); // navbar
        $page = new AuthDecorator($page); // Auth System
        return new SocialMediaDecorator($page);
    }

    public function get(): ?AbstractHTMLComponent
    {
        return $this->htmlComp = (new HTMLBaseComponent(''))->setLevel(0);
    }

    protected function model(string|AbstractDataFromCache $m) : Object
    {
        if (is_string($m)) {
            return Application::diGet(ModelFactory::class)->create($m);
        }
        if (CustomReflector::getInstance()->isInitialized('model', $m)) {
            return $m->getModel();
        } else {
            return Application::diGet(ModelFactory::class)->create($m->getModelName());
        }
    }
}