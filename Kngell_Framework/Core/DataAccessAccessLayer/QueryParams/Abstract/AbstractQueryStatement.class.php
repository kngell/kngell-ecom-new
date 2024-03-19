<?php

declare(strict_types=1);

abstract class AbstractQueryStatement implements QueryStatementInterface
{
    protected ?AbstractQueryStatement $parent;
    protected ?CollectionInterface $children;
    protected ?QueryParamsHelper $helper;
    protected ?Token $token;
    protected int $level = 0;
    protected ?string $queryType;
    protected ?string $queryStatement;
    protected ?string $tbl;
    protected ?string $alias;
    protected string|Closure|null $method;
    protected ?string $baseMethod;
    protected string $braceOpen = '';
    protected string $braceClose = '';
    protected string $statement;
    protected string $query = '';
    protected array $bind_arr = [];
    protected array $parameters = [];
    protected array $fields = [];
    protected array $tableAlias = [];
    protected array $aliasCheck = [];

    public function __construct(string|Closure|null $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        $this->children = new Collection();
        $this->helper = new QueryParamsHelper();
        $this->token = Application::diGet(Token::class);
        $this->method = $method;
        $this->baseMethod = $baseMethod;
        $this->queryType = $queryType;
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
    public function getMethod(): string|Closure|null
    {
        return $this->method;
    }

    /**
     * Set the value of method.
     */
    public function setMethod(string|Closure|null $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get the value of alias.
     */
    public function getAlias(): bool|string
    {
        return isset($this->alias) ? $this->alias : false;
    }

    /**
     * Set the value of alias.
     */
    public function setAlias(?string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get the value of statement.
     */
    public function getStatement(): string|bool
    {
        return isset($this->statement) ? $this->statement : false;
    }

    /**
     * Set the value of statement.
     */
    public function setStatement(string $statement): self
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * Get the value of query.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Set the value of query.
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of tableAlias.
     */
    public function getTableAlias(): array
    {
        return $this->tableAlias;
    }

    /**
     * Set the value of tableAlias.
     */
    public function setTableAlias(array $tableAlias): self
    {
        $this->tableAlias = $tableAlias;

        return $this;
    }

    /**
     * Get the value of aliasCheck.
     */
    public function getAliasCheck(): array
    {
        return $this->aliasCheck;
    }

    /**
     * Set the value of aliasCheck.
     */
    public function setAliasCheck(array $aliasCheck): self
    {
        $this->aliasCheck = $aliasCheck;

        return $this;
    }

    /**
     * Get the value of queryType.
     */
    public function getQueryType(): ?string
    {
        return $this->queryType;
    }

    /**
     * Set the value of queryType.
     */
    public function setQueryType(?string $queryType): self
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * Get the value of helper.
     */
    public function getHelper(): ?QueryParamsHelper
    {
        return $this->helper;
    }

    /**
     * Set the value of helper.
     */
    public function setHelper(?QueryParamsHelper $helper): self
    {
        $this->helper = $helper;

        return $this;
    }

    /**
     * Get the value of baseMethod.
     */
    public function getBaseMethod(): ?string
    {
        return $this->baseMethod;
    }

    /**
     * Set the value of baseMethod.
     */
    public function setBaseMethod(?string $baseMethod): self
    {
        $this->baseMethod = $baseMethod;

        return $this;
    }

    protected function tablesGet(self $child) : void
    {
        $this->tableAlias = $child->getTableAlias();
        $this->aliasCheck = $child->getAliasCheck();
    }

    protected function tablesSet(self $child) : self
    {
        $child->setTableAlias($this->tableAlias);
        $child->setAliasCheck($this->aliasCheck);
        return $child;
    }

    protected function useBrace() : void
    {
        if (in_array($this->queryType, ['INSERT', 'UPDATE', 'WITHCTE'])) {
            $this->braceOpen = ' (';
            $this->braceClose = ') ';
        }
    }

    protected function statement(string|int $stmt) : string|int
    {
        if (is_string($stmt) && ! empty($this->statement)) {
            $r = explode($this->statement, $stmt);
            return implode('', $r);
        }
        return $stmt;
    }

    protected function link() : string
    {
        return match (true) {
            str_starts_with($this->method, 'where') => ' AND ',
            str_starts_with($this->method, 'on') => ' AND ',
            str_starts_with($this->method, 'having') => ' AND ' ,
            str_starts_with($this->method, 'or') => ' OR ',
            str_starts_with($this->method, 'set') => ' , ',
            default => '',//throw new BadQueryArgumentException('No Maches Method'),
        };
    }
}