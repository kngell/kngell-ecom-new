<?php

declare(strict_types=1);
class AuthLoginBoxHTMLElement extends AbstractHTMLElement
{
    private string $loginLabel = '<div>&nbspRemember Me&nbsp</div>';
    private string $section = 'loginBox';

    public function __construct(?string $template = null, ?array $params = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $box = str_replace('{{loginForm}}', $this->loginForm(), $this->template);
        return[$this->section, $box];
    }

    private function loginForm() : string
    {
        $frm = isset($this->params['frm']) ? $this->params['frm'] : '';
        $template = isset($this->params['loginTemplate']) ? $this->params['loginTemplate'] : '';
        $form = $frm->setTemplate($template);
        $print = $form->getPrint();
        $form->form([
            'action' => '',
            'id' => 'login-frm',
            'class' => ['login-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $loginTemplate = str_replace('{{form_begin}}', $form->begin(), $template);
        $loginTemplate = str_replace('{{email}}', $form->input($print->email(name:'email'))
            ->placeholder(' ')
            ->class(['email'])
            ->id('email')
            ->label('Email :')
            ->html(), $loginTemplate);
        $loginTemplate = str_replace('{{password}}', $form->input($print->password(name:'password'))
            ->placeholder(' ')
            ->id('password')
            ->Label('Password :')
            ->html(), $loginTemplate);
        $loginTemplate = str_replace('{{remamber_me}}', $form->input($print->checkbox(name:'remember_me'))
            ->labelClass(['checkbox'])
            ->label($this->loginLabel)
            ->spanClass(['checkbox__box text-danger'])
            ->id('remember_me')
            ->html(), $loginTemplate);
        $loginTemplate = str_replace('{{submit}}', $form->input($print->submit(name: 'sigin'))
            ->label('Login')->id('sigin')
            ->html(), $loginTemplate);
        return str_replace('{{form_end}}', $form->end(), $loginTemplate);
    }
}