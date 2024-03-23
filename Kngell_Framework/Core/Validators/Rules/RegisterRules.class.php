<?php

declare(strict_types=1);

class RegisterRules implements RulesInterface
{
    public function getRules(): array
    {
        return [
            'terms' => [
                'required' => true,
                'display' => '',
            ],
            'firstName' => [
                'required' => true,
                'min' => 2,
                'max' => 64,
                'display' => 'Firstname',
                'valid_string' => true,
            ],
            'lastName' => [
                'required' => true,
                'min' => 2,
                'max' => 64,
                'display' => 'Lastname',
                'valid_string' => true,
            ],
            'userName' => [
                'display' => 'Username',
                'required' => true,
                'unique' => 'users',
                'min' => 2,
                'max' => 20,
                // 'Valid_string' => true,
            ],
            'email' => [
                'display' => 'Email',
                'required' => true,
                'unique' => 'users',
                'max' => 150,
                'valid_email' => true,
                // 'Valid_string' => true,
                // 'Valid_domain' => true,
            ],
            'password' => [
                'display' => 'Password',
                'required' => true,
                'min' => 8,
                'max' => 64,
                'valid_password' => true,
            ],
            'cpassword' => [
                'display' => 'Confirm Password',
                'required' => true,
                'min' => 8,
                'matches' => 'password',
                'valid_password' => true,
            ],
        ];
    }
}