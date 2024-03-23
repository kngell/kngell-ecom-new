<?php

declare(strict_types=1);
class MaxValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        $value = $this->userData[$this->field];
        if (strlen($value) >= $this->field) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }
        return strlen($value) < $this->field;
    }
}