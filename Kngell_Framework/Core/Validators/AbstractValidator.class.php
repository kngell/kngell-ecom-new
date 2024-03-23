<?php

declare(strict_types=1);

abstract class AbstractValidator
{
    protected ValidatorRulesFactory $factory;
    protected AbstractController $controller;
    protected array $userData = [];
    protected string $errorName = 'error-field';

    public function __construct(AbstractController $controller, array $userData, ValidatorRulesFactory $factory)
    {
        $this->userData = $userData;
        $this->factory = $factory;
        $this->controller = $controller;
    }

    public function runValidator(string $ruleSetName) : array
    {
        $itemsList = RulesFactory::getItems($ruleSetName);
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
        return $errors;
    }

    private function display(array $rules) : array
    {
        if (isset($rules['display'])) {
            $display = $rules['display'];
            unset($rules['display']);
        }
        return [$display, $rules];
    }
}