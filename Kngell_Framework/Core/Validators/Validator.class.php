<?php

declare(strict_types=1);

class Validator implements ValidatorInterface
{
    public function __construct(AbstractController $controller, array $userData)
    {
    }

    public function validate() : bool
    {
        $itemsList = RulesFactory::getItems($this->rules());
        $errors = [];
        $display = '';
        ! isset($this->userData['terms']) ? $this->userData['terms'] = '' : '';
        foreach ($itemsList as $item => $rules) {
            if (array_key_exists($item, $this->userData)) {
                list($display, $rules) = $this->display($rules);
                $ruleSet = $this->factory->create($rules, $this->userData, $item);
                foreach ($ruleSet as $rule) {
                    $noPassed = $rule->validate($display);
                    if (is_string($noPassed)) {
                        ($noPassed !== 'noPassed' || $noPassed !== 'noPassed' && ! isset($ruleSet['required'])) ? $errors[$item][] = $noPassed : '';
                    }
                }
            }
        }
        return $this->errors($errors);
    }

    private function rules() : string
    {
        $controllerArr = explode('_', StringUtil::separate($this->controller::class));
        if (! isset($controllerArr[0])) {
            throw new ValidatorException('Rules are not defined! Please Match the controller with the rules');
        }
        return $this->validatorRules = $controllerArr[0];
    }

    private function display(array $rules) : array
    {
        if (isset($rules['display'])) {
            $display = $rules['display'];
            unset($rules['display']);
        }
        return [$display, $rules];
    }

    private function errors(array $errors) : bool
    {
        if (! empty($errors)) {
            $errors = ArrayUtil::transform_keys($errors, [
                'password' => 'reg_password',
                'email' => 'reg_email',
            ]);
            $this->controller->getResponse()->jsonResponse(['result' => 'error-field', 'msg' => $errors]);
        }
        return true;
    }
}
