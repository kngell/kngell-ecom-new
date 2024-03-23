<?php

declare(strict_types=1);
class MatchesValidator extends AbstractRulesValidator
{
    public function __construc(mixed $rule, array $userData, string $field)
    {
        parent::__construct($rule, $userData, $field);
    }

    public function validate(string $display) : mixed
    {
        if (! isset($this->userData['password'])) {
            throw new ValidatorException('Password is not define');
        }
        $matches = $this->userData[$this->rule];
        $value = $this->userData[$this->field];
        if ($value !== $matches) {
            return ValidatorsContext::getErrMessage($this::class, $this->field, $this->rule, $display);
        }

        return $value === $matches;
    }
}