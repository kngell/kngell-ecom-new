<?php

declare(strict_types=1);
abstract class AbstractRulesValidator
{
    protected mixed $rule;
    protected array $userData;
    protected string $field;
    protected ModelFactory $factory;
    // private mixed $rule;
    // private Model $_model;
    // private string $msg;
    // private mixed $field;

    public function __construct(mixed $rule, array $userData, string $field)
    {
        $this->rule = $rule;
        $this->userData = $userData;
        $this->field = $field;
        $this->factory = Application::diGet(ModelFactory::class);
        // $this->_model = $model;
        // $this->field = $field;
        // $this->rule = $rule;
        // $this->msg = $msg;
    }

    abstract public function validate(string $display) : mixed;

    // public function run()
    // {
    //     try {
    //         return $this->runValidation();
    //     } catch (Exception $e) {
    //         throw new BaseException('Validation Exception on ' . get_class() . ' : ' . $e->getMessage(), 1);
    //     }
    // }

    // public function getModel() : Model
    // {
    //     return $this->_model;
    // }

    // public function getField() : mixed
    // {
    //     return $this->field;
    // }

    // public function getRule() : mixed
    // {
    //     return $this->rule;
    // }

    // public function getMsg() : string
    // {
    //     return $this->msg;
    // }

    /**
     * Get the value of rule.
     */
    public function getRule(): mixed
    {
        return $this->rule;
    }

    /**
     * Set the value of rule.
     */
    public function setRule($rule): self
    {
        $this->rule = $rule;

        return $this;
    }
}