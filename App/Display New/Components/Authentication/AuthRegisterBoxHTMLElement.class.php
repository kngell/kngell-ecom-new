<?php

declare(strict_types=1);
class AuthRegisterBoxHTMLElement extends AbstractHTMLElement
{
    protected string $registerLabel = '<div>J\'accepte&nbsp;<a href="#">les termes&nbsp;</a>&amp;&nbsp;<a href="#">conditions</a> d\'utilisation</div>';
    private string $section = 'registerBox';

    public function __construct(?string $template = null, ?array $params = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $box = str_replace('{{registerForm}}', $this->registerForm(), $this->template);
        return[$this->section, $box];
    }

    private function registerForm() : string
    {
        $frm = isset($this->params['frm']) ? $this->params['frm'] : '';
        $template = isset($this->params['registerTemplate']) ? $this->params['registerTemplate'] : '';

        /** @var FormBuilder */
        $form = $frm->setTemplate($template);
        $form->form([
            'id' => 'register-frm',
            'class' => ['register-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $registerTemplate = str_replace('{{form_begin}}', $form->begin(), $template);
        $registerTemplate = str_replace('{{camera}}', ImageManager::asset_img('camera' . DS . 'camera-solid.svg'), $registerTemplate);
        $registerTemplate = str_replace('{{avatar}}', ImageManager::asset_img('users' . DS . 'avatar.png'), $registerTemplate);
        $registerTemplate = str_replace('{{lastName}}', $form->input([
            TextType::class => ['name' => 'lastName'],
        ])->placeholder(' ')->label('Last Name :')->id('lastName')->req()->html(), $registerTemplate);
        $registerTemplate = str_replace('{{firstName}}', $form->input([
            TextType::class => ['name' => 'firstName'],
        ])->placeholder(' ')->label('First Name :')->id('firstName')->req()->html(), $registerTemplate);
        $registerTemplate = str_replace('{{username}}', $form->input([
            TextType::class => ['name' => 'user_name'],
        ])->placeholder(' ')->label('UserName')->id('user_name')->html(), $registerTemplate);
        $registerTemplate = str_replace('{{email}}', $form->input([
            EmailType::class => ['name' => 'email', 'id' => 'reg_email'],
        ])->placeholder(' ')->label('Email :')->req()->html(), $registerTemplate);
        $registerTemplate = str_replace('{{password}}', $form->input([
            PasswordType::class => ['name' => 'password', 'id' => 'reg_password'],
        ])->placeholder(' ')->label('Password :')->req()->html(), $registerTemplate);
        $registerTemplate = str_replace('{{cpassword}}', $form->input([
            PasswordType::class => ['name' => 'cpassword'],
        ])->placeholder(' ')->label('Confirm Password :')->id('cpassword')->req()->html(), $registerTemplate);
        $registerTemplate = str_replace('{{terms}}', (string) $form->input([
            CheckboxType::class => ['name' => 'terms', 'id' => 'terms'],
        ])->label($this->registerLabel)->labelClass(['checkbox'])->spanClass(['checkbox__box text-danger'])->req(), $registerTemplate);
        $registerTemplate = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'reg_singin'], ], null, ['show_label' => false,
            ])->label('Register')->id('reg_singin'), $registerTemplate);
        return str_replace('{{form_end}}', $form->end(), $registerTemplate);
    }
}