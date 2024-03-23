<?php

declare(strict_types=1);
enum ValidatorsContext : string
{
    case RequireValidator = 'required';
    case EmailValidator = 'valid_email';
    case MinValidator = 'min';
    case MaxValidator = 'max';
    case StringValidator = 'valid_string';
    case UniqueValidator = 'unique';
    case PasswordValidator = 'valid_password';
    case MatchesValidator = 'matches';

    private const VALIDATOR_MSG = [
        'required' => [
            'terms' => 'Terms and Conditions are required!',
            'noTerms' => '{{display}} is required',
        ],
        'min' => '{{display}} must have at least {{rule}} characters',
        'max' => '{{display}} must be a maximum of {{rule}} caracters',
        'valid_email' => '{{display}} is not a valid Email',
        'is_numeric' => '{{display}} has to be a number. Please use a numeric value',
        'matches' => '{{display}} does not match {{rule}}',
        'unique' => 'This {{display}} already exist.',
        'valid_string' => '{{display}} is not valid',
        'valid_password' => '{{display}} must contains upper, lower cases and special char',
        'valid_domain' => '{{display}} does not have a valid Domain name!',
    ];

    public static function getErrMessage(string $validator, ?string $item, mixed $rule, string $display) : string
    {
        $k = self::getValidator($validator);

        $msg = self::VALIDATOR_MSG[$k];
        if ($k == 'required') {
            if (array_key_exists($item, $msg)) {
                $msg = $msg[$item];
            } else {
                $msg = $msg['noTerms'];
            }
        }
        $msg = str_replace('{{display}}', $display, $msg);
        return str_replace('{{rule}}', (string) $rule, $msg) . '</br>';
    }

    private static function getValidator(string $validator) : string
    {
        foreach (self::cases() as $case) {
            if ($case->name === $validator) {
                return $case->value;
            }
        }
        throw new ValidatorException('Validator is not define');
    }
}