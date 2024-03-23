<?php

declare(strict_types=1);

class LoginValidator extends AbstractValidator implements ValidatorInterface
{
    public function __construct(AbstractController $controller, array $userData, ValidatorRulesFactory $factory)
    {
        parent::__construct($controller, $userData, $factory);
    }

    public function validate(): bool
    {
        $errors = $this->runValidator('login');
        if (empty($errors)) {
            return true;
        }
        $this->controller->getResponse()->jsonResponse([
            'result' => $this->errorName,
            'msg' => $errors,
        ]);
    }
}