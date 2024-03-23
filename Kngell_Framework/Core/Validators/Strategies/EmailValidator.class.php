<?php

declare(strict_types=1);
class EmailValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        $value = $this->userData[$this->field];
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    // public function runValidation()
    // {
    //     $getter = $this->getModel()->getEntity()->getGetters($this->getField());
    //     $value = $this->getModel()->getEntity()->{$getter}();
    //     if (! (! empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false)) {
    //         return false;
    //     }
    //     if (! checkdnsrr(substr($value, strpos($value, '@') + 1), 'MX')) {
    //         return false;
    //     }
    //     return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    // }
}