<?php

declare(strict_types=1);

interface FormBuilderTypeInterface
{
    /**
     * Options which are defined for this object type
     * Pass the default array to the parent::configureOptions to merge together.
     *
     * @param array $options|$extensionOptions
     * @return void
     * @throws FormBuilderInvalidArgumentException
     * @throws FormBuilderOutOfBoundsException
     */
    public function configureOptions(array $options = []) : void;

    /**
     * Publicize the default object type to other classes.
     *
     * @return string
     */
    public function getType() : string;

    /**
     * Publicize the default object options to the base class.
     *
     * @return array
     */
    public function getOptions() : array;

    /**
     * Return the third argument from the add() method. This array can be used
     * to modify and filter the final output of the input and HTML wrapper.
     *
     * @return array
     */
    public function getSettings() : array;

    /**
     * The pre filter method provides a way to filtered the build field input
     * on a a per object type basis as all types share the same basic tags.
     *
     * there are cases where a tag is not required or valid within a
     * particular input/field. So we can filter it out here before being sent
     * back to the controller class
     *
     * @return string - return the filtered or unfiltered string
     */
    public function filtering(): string;

    /**
     * Render the form view to the builder method within the base class.
     *
     * @return mixed
     */
    public function view(): mixed;

    public function htmlAttr() : array;
}
