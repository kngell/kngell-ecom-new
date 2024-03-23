<?php

declare(strict_types=1);

class RulesFactory
{
    public static function getItems(string $context) : array
    {
        $validatorString = RulesContext::from($context);
        $validatorObj = new $validatorString->name;
        if (! $validatorObj instanceof RulesInterface) {
            throw new ValidatorException("$validatorString is not valid");
        }
        return $validatorObj->getRules();
    }
}