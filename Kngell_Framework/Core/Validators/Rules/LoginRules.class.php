<?php

declare(strict_types=1);

class LoginRules implements RulesInterface
{
    public function getRules(): array
    {
        return [
            'email' => [
                'display' => 'Email',
                'required' => true,
                'valid_email' => true,
            ],
            'password' => [
                'display' => 'Password',
                'required' => true,
                'min' => 5,
            ],
        ];
    }
}