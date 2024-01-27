<?php

declare(strict_types=1);

abstract class AbstractQueryStatement implements QueryStatementInterface
{
    protected ?AbstractQueryStatement $parent;
    protected ?CollectionInterface $children;
    protected ?QueryParamsHelper $helper;
    protected int $level;
    protected ?string $queryStatement;
    protected ?string $tbl;
    protected ?string $tblAlias;
    protected ?string $method;
    protected string $braceOpen = '';
    protected string $braceClose = '';

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        ! isset($this->children) ? $this->children = $children : '';
        $this->helper = $helper;
        $this->method = $method;
    }

    abstract public function proceed() : array;

    public function add(self $conditionObj): self
    {
        $conditionObj->level = $this->level + 1;
        $this->children->add($conditionObj);
        $conditionObj->setParent($this);
        return $this;
    }

    public function isComposite(): bool
    {
        return false;
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
     * Get the value of queryStatement.
     */
    public function getQueryStatement(): ?string
    {
        return $this->queryStatement;
    }

    /**
     * Set the value of queryStatement.
     */
    public function setQueryStatement(?string $queryStatement): self
    {
        $this->queryStatement = $queryStatement;

        return $this;
    }

    /**
     * Get the value of tbl.
     */
    public function getTbl(): ?string
    {
        return $this->tbl;
    }

    /**
     * Set the value of tbl.
     */
    public function setTbl(?string $tbl): self
    {
        $this->tbl = $tbl;

        return $this;
    }

    /**
     * Get the value of tblAlias.
     */
    public function getTblAlias(): ?string
    {
        return $this->tblAlias;
    }

    /**
     * Set the value of tblAlias.
     */
    public function setTblAlias(?string $tblAlias): self
    {
        $this->tblAlias = $tblAlias;

        return $this;
    }

    /**
     * Get the value of parent.
     */
    public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * Set the value of parent.
     */
    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the value of children.
     */
    public function getChildren(): ?CollectionInterface
    {
        return $this->children;
    }

    /**
     * Set the value of children.
     */
    public function setChildren(?CollectionInterface $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get the value of method.
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * Set the value of method.
     */
    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    protected function link() : string
    {
        return match (true) {
            str_starts_with($this->method, 'where') => ' AND ',
            str_starts_with($this->method, 'on') => ' AND ',
            str_starts_with($this->method, 'having') => ' AND ' ,
            str_starts_with($this->method, 'or') => ' OR ',
            default => '',//throw new BadQueryArgumentException('No Maches Method'),
        };
    }
}