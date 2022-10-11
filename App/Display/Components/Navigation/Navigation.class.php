<?php

declare(strict_types=1);

class Navigation extends AbstractNavigation implements DisplayPagesInterface
{
    public function __construct(NavigationPath $paths, ?CollectionInterface $settings = null, ?DisplaySearchBox $searchBox = null, ?array $cartItem = [], ?View $view = null)
    {
        parent::__construct($paths, $settings, $searchBox, $cartItem, $view);
    }

    public function displayAll(): array
    {
        return [
            'navComponent' => $this->outputNavComponent($this->getTemplate('navPath')),
        ];
    }

    private function outputNavComponent(?string $template = null) : string
    {
        if ($template != null) {
            $template = str_replace('{{settings}}', $this->settings(), $template);
            $template = str_replace('{{searchBox}}', $this->searchBox->displayAll()['search_box'], $template);
            $template = str_replace('{{connection}}', $this->connexion(), $template);
            $template = str_replace('{{cartItems}}', $this->cartItem['cartItems'], $template);
            $template = str_replace('{{navbar-brand}}', $this->navBrand(), $template);
            $template = str_replace('{{menu}}', $this->menu(), $template);
        }

        return $template;
    }
}
