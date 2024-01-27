<?php

declare(strict_types=1);

abstract class AbstractConditions
{
    protected array $conditions = [];
    protected int $level;
    protected ?string $method;
    protected ?string $tbl;
    protected ?string $tblAlias;
    protected string $braceOpen = '';
    protected string $braceClose = '';
    protected ?AbstractConditions $component;
    protected CollectionInterface $childrenStorage;

    public function __construct(array $conditions, ?string $method, ?string $tbl = null, ?string $tblAlias = null)
    {
        $this->conditions = $conditions;
        $this->childrenStorage = new Collection();
        $this->method = $method;
        $this->tbl = $tbl;
        $this->tblAlias = $tblAlias;
    }

    public function isComposite(): bool
    {
        return false;
    }

    abstract public function proceed() : array;

    /**
     * Get the value of component.
     */
    public function getComponent(): self|null
    {
        return $this->component;
    }

    /**
     * Set the value of component.
     */
    public function setComponent(self|null $component): self
    {
        $this->component = $component;
        return $this;
    }

    /**
     * Get the value of level.
     */
    public function getLevel(): int|bool
    {
        if (isset($this->level)) {
            return $this->level;
        }
        return false;
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
     * Get the value of conditions.
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Set the value of conditions.
     */
    public function setConditions(array $conditions): self
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * Get the value of childrenStorage.
     */
    public function getChildrenStorage(): CollectionInterface
    {
        return $this->childrenStorage;
    }

    /**
     * Get the value of tbl.
     */
    public function getTbl(): ?string
    {
        return $this->tbl;
    }

    protected function link() : string
    {
        return match (true) {
            in_array($this->method, ['where', 'on', 'having']) => ' AND ',
            in_array($this->method, ['orWhere', 'orOn']) => ' OR ',
            default => ' AND ',//throw new BadQueryArgumentException('No Maches Method'),
        };
    }
}