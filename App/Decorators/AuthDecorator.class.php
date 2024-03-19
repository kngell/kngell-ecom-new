<?php

declare(strict_types=1);

class AuthDecorator extends AbstractPageDecorator
{
    protected const PROPERTIES = [
        'paths' => AuthFilePath::class,
        'frm' => FormBuilder::class,
    ];
    protected ?FormBuilder $frm;

    public function __construct(?AbstractController $controller = null)
    {
        parent::__construct($controller);
        $this->initProperties();
    }

    public function get() : AbstractHTMLComponent
    {
        $authComponent = new AuthHTMLComponent($this->getTemplate('authTemplatePath'));
        $authComponent->setLevel(1);
        $params = [
            'frm' => $this->frm,
            'loginTemplate' => $this->getTemplate('loginTemplatePath'),
        ];
        $authComponent->add(new AuthLoginBoxHTMLElement($this->getTemplate('loginboxPath'), $params));

        $params['registerTemplate'] = $this->getTemplate('registerTemplatePath');
        $authComponent->add(new AuthRegisterBoxHTMLElement($this->getTemplate('registerboxPath'), $params));

        $params['forgotPwTemplate'] = $this->getTemplate('forgotPwTemplatePath');
        $authComponent->add(new AuthForgotPwBoxHTMLElement($this->getTemplate('forgotboxPath'), $params));

        return  $this->controller->get()->add($authComponent);
    }
}