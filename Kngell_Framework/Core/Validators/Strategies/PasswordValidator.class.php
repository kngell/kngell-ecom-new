<?php

declare(strict_types=1);
class PasswordValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        $value = $this->userData[$this->field];
        $m = preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', $value, $matches);
        if (! $m) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }
        return $m;
    }
}