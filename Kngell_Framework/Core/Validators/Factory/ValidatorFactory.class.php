<?php

declare(strict_types=1);

class ValidatorFactory
{
    public function __construct(private ValidatorRulesFactory $factory)
    {
    }

    public function create(AbstractController $controller, array $userData) : ValidatorInterface
    {
        $controllerArr = explode('_', StringUtil::separate($controller::class));
        if (! isset($controllerArr[0])) {
            throw new ValidatorException('Controller Name does not match separation rules');
        }
        $validator = match ($controllerArr[0]) {
            'login' => new LoginValidator($controller, $userData, $this->factory),
            'register' => new RegisterValidator($controller, $userData, $this->factory),
            default => new NullUserValidator($controller, $userData, $this->factory),
        };
        if (! $validator instanceof ValidatorInterface) {
            throw new ValidatorException("{$controllerArr[0]} is not a valid instance ValidatorInterface");
        }
        return $validator;
    }
}