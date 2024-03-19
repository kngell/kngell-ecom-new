<?php

declare(strict_types=1);

class NavBarDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => NavigationPath::class,
        'userCart' => UserCartHTMLElement::class,
        'form' => SearchBoxForm::class,
        'settings' => SettingsFromCache::class,
    ];
    protected ?UserCartHTMLInterface $userCart;
    protected ?SearchBoxForm $form;
    protected ?SettingsFromCache $settings;

    public function __construct(?AbstractController $controller)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get() : AbstractHTMLComponent
    {
        $menu = GrantAccess::getInstance()->getMenu('menu_acl');
        $navBar = new NavBar($this->getTemplate('navPath'));
        $navBar->setLevel(1);
        $navBar->add(new BtnConnextionElement($this->getTemplate('conectPath'), [
            'menuTemplate' => $this->getTemplate('menuConnexionPath'),
            'user' => AuthManager::user(),
            'dropMenu' => $menu->offsetGet('menu_admin_user'),
            'whishlistItems' => $this->userCart->getWhishlistItems(),
        ], false));
        $navBar->add(new CartItemsElement('', ['cartItems' => $this->userCart->getCartItems()]));
        $navBar->add(new MenuElement('', ['menu' => $menu]));
        $navBar->add(new NavBrandElement($this->getTemplate('navBrandPath'), ['route' => $this->controller->view()->route('/')]));
        $navBar->add(new SearchBoxElement($this->form->createForm(''), []));
        $navBar->add(new SettingsElement($this->getTemplate('settingsPath'), ['settings' => $this->settings->get()]));

        return  $this->controller->get()->add($navBar);
    }
}