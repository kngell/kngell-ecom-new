<?php

declare(strict_types=1);
class AuthForgotPwBoxHTMLElement extends AbstractHTMLElement
{
    private string $section = 'forgotPwBox';

    public function __construct(?string $template = null, ?array $params = null)
    {
        parent::__construct($template, $params);
    }

    public function display(): array
    {
        $box = str_replace('{{forgotPassword}}', $this->forgotForm(), $this->template);
        return[$this->section, $box];
    }

    protected function forgotForm() : string
    {
        $frm = isset($this->params['frm']) ? $this->params['frm'] : '';
        $template = isset($this->params['forgotPwTemplate']) ? $this->params['forgotPwTemplate'] : '';
        $form = $frm->setTemplate($template);
        $form->form([
            'action' => '',
            'id' => 'forgot-frm',
            'class' => ['forgot-frm'],
            'enctype' => 'multipart/form-data',
        ]);
        $template = str_replace('{{form_begin}}', $form->begin(), $template);
        $template = str_replace('{{email}}', (string) $form->input([
            EmailType::class => ['name' => 'email', 'id' => 'forgot_email'],
        ])->placeholder('Email :')->class(['email'])->noLabel(), $template);
        $template = str_replace('{{submit}}', (string) $form->input([
            SubmitType::class => ['name' => 'forgot'],
        ])->label('Reset Password'), $template);
        return str_replace('{{form_end}}', $form->end(), $template);
    }
}