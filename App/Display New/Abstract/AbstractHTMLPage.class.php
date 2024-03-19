<?php

declare(strict_types=1);
abstract class AbstractHTMLPage
{
    protected self $parent;
    protected int $level;
    protected ?string $template;

    public function __construct(?string $template = null)
    {
        $this->template = $template;
    }

    abstract public function display() : array;

    /**
     * Get the value of parent.
     */
    public function getParent(): self
    {
        return $this->parent;
    }

    /**
     * Set the value of parent.
     */
    public function setParent(self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the value of level.
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Set the value of level.
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get the value of template.
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Set the value of template.
     */
    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }
}