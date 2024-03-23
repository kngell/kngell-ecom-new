<?php

declare(strict_types=1);

class RegisterValidator extends AbstractValidator implements ValidatorInterface
{
    public function __construct(AbstractController $controller, array $userData, ValidatorRulesFactory $factory)
    {
        parent::__construct($controller, $userData, $factory);
    }

    public function validate(): bool
    {
        $errors = $this->runValidator('register');
        if (empty($errors)) {
            return true;
        }
        $newKeys = [
            'password' => 'reg_password',
            'email' => 'reg_email',
        ];
        $errors = ArrayUtil::transform_keys($errors, $newKeys);
        $this->controller->getResponse()->jsonResponse([
            'result' => $this->errorName,
            'msg' => $errors,
        ]);
    }
}