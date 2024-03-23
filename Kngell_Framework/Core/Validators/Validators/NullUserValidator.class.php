<?php

declare(strict_types=1);

class NullUserValidator extends AbstractValidator implements ValidatorInterface
{
    public function __construct(AbstractController $controller, array $userData, ValidatorRulesFactory $factory)
    {
        parent::__construct($controller, $userData, $factory);
    }

    public function validate(): bool
    {
        return false;
    }
}