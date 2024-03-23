<?php

declare(strict_types=1);

class ValidatorRulesFactory
{
    public function create(array $rules, array $userData, string $field) : array
    {
        $ruleSet = [];
        foreach ($rules as $validator_key => $rule) {
            $validatorString = ValidatorsContext::from($validator_key)->name;
            if (class_exists($validatorString)) {
                $validatorObj = new $validatorString($rule, $userData, $field);
                if (! $validatorObj instanceof AbstractRulesValidator) {
                    throw new ValidatorException("$validatorString is not a valid validator object");
                }
                $ruleSet[$validator_key] = $validatorObj;
            }
        }
        return $ruleSet;
    }
}