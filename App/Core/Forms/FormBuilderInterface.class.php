<?php

declare(strict_types=1);

interface FormBuilderInterface
{
    /**
     * Undocumented function.
     *
     * @param array $args
     * @return FormBuilderInterface
     */
    public function form(array $args = []) : self;

    /**
     * This method allows us to chain multiple input types together to build the required
     * form structure.
     *
     * @param array $args - optional argument to modified the values of the input wrapping tag
     * @param null $options
     * @return mixed
     */
    public function add(array $args = [], $options = null, array $settings = []) : self;

    /**
     * This methods get chain at the very end after each add() method. And will attempt to build
     * the required input based on each add() method arguments. Theres an option to have
     * HTML elements wrap around each input tag for better styling of each element.
     *
     * @param array $args
     * @return string|bool
     */
    public function build(array $args = []): string|bool;

    public function html() : string;

    /**
     * @return array
     */
    public function canHandleRequest() : array;

    /**
     * Check whether the form is submittable. Submit button should represent
     * the argument name.
     *
     * @param string $name - default to <input type="submit" name="submit">
     * @return bool
     */
    public function isSubmittable(string $name = 'submit') : bool;

    /**
     * Instantiate the external csrf fields.
     *
     * @param mixed|null $lock
     * @return string
     */
    public function csrfForm(mixed $lock = null): string;

    /**
     * Wrapper function for validating csrf token.
     *
     * @return bool
     */
    public function csrfValidate(): bool;

    public function globalClasses(array $classes) : self;

    public function input(array $args = [], $options = null, array $settings = []) : self;

    public function hiddenInputs(array $args = []) : string;

    public function begin(string $alertid = '') : string;

    public function replaceText(?string $template = null, ?string $stringToreplace = null, array $args = []) : string;

    public function end() : string;
}
