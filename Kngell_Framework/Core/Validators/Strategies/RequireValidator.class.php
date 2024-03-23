<?php

declare(strict_types=1);
class RequireValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        if ($this->field == 'terms' && ! isset($this->userData[$this->field])) {
            return false;
        }
        $value = $this->userData[$this->field];
        if (empty($value) === $this->rule) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }
        return ! empty($value) === $this->rule;
    }
}