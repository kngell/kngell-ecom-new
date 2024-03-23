<?php

declare(strict_types=1);
class StringValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        $value = $this->userData[$this->field];
        if (! preg_match('/^[a-zA-Z- ]+$/', $value)) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }
        return preg_match('/^[a-zA-Z- ]+$/', $value);
    }

    // public function runValidation()
    // {
    //     $getter = $this->getModel()->getEntity()->getGetters($this->getField());
    //     $value = $this->getModel()->getEntity()->{$getter}();

    //     return preg_match('/^[a-zA-Z- ]+$/', $value);
    // }
}