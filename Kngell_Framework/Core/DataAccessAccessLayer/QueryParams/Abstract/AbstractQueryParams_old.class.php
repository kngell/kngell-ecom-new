<?php

declare(strict_types=1);

abstract class AbstractQueryParams_old
{
    use QueryParamsTrait;

    protected ?QueryType $queryType;
    protected MainQuery $query;
    protected QueryParamsHelper $helper;
    protected StatementFactory $stmtFactory;
    protected FieldsStatement $select;
    protected FieldsStatement $fields;
    protected QueryStatement $table;
    protected QueryStatement $raw;
    protected QueryStatement $from;
    protected QueryStatement $join;
    protected ConditionStatement $where;
    protected ConditionStatement $on;
    protected ConditionStatement $having;
    protected ConditionStatement $set;
    protected GroupAndSortStatement $groupBy;
    protected GroupAndSortStatement $orderBy;
    protected CounterStatement $limit;
    protected CounterStatement $offset;
    protected QueryStatement $insert;
    protected QueryStatement $update;
    protected QueryStatement $delete;
    protected FieldsStatement $values;
    protected Entity $entity;
    protected Token $token;
    protected array $queryParams = [];
    protected string $currentTable;
    protected string $alias;
    protected string $method;
    protected string $lastMethod;
    protected array $tableAlias;
    protected array $aliasCheck = [];
    protected array $params = [];
    protected array $columns = [];
    protected array $updateSet = [];
    protected array $arr_values = [];
    protected ?string $joinTable = null;
    protected bool $selectStatus;
    protected bool $fromStatus;
    protected bool $insertStatus;
    protected bool $intoStatus;
    protected bool $fieldStatus;
    protected bool $valuesSatatus;
    protected bool $updateStatus;
    protected bool $setStatus;
    protected bool $deleteStatus;
    protected bool $rawStatus;
    protected bool $dataFromEntities = false;

    public function __construct(MainQuery $query, QueryParamsHelper $helper, Token $token, StatementFactory $stFactory)
    {
        $this->query = $query;
        $this->helper = $helper;
        $this->token = $token;
        $this->stmtFactory = $stFactory;
        $this->query->setLevel(0);
    }

    /**
     * Get the value of query.
     */
    public function getQuery(): MainQuery
    {
        return $this->query;
    }

    /**
     * Get the value of queryType.
     */
    public function getQueryType(): ?QueryType
    {
        return $this->queryType;
    }

    /**
     * Set the value of queryType.
     */
    public function setQueryType(?QueryType $queryType): self
    {
        $this->queryType = $queryType;

        return $this;
    }

    /**
     * Get the value of dataFromEntities.
     */
    public function isDataFromEntities(): bool
    {
        return $this->dataFromEntities;
    }

    /**
     * Set the value of dataFromEntities.
     */
    public function setDataFromEntities(bool $dataFromEntities): self
    {
        $this->dataFromEntities = $dataFromEntities;

        return $this;
    }

    protected function tableAlias(string $tbl) : array
    {
        $alias = '';
        $t = '';
        if ($tbl !== null) {
            $parts = explode('|', $tbl);
            if (count($parts) == 1) {
                $alias = strtolower($tbl[0]);
                $t = $tbl;
            } elseif (count($parts) == 2) {
                $alias = $parts[1];
                $t = $parts[0];
            }
            if (isset($this->tableAlias) && array_key_exists($t, $this->tableAlias)) {
                return [$this->tableAlias[$t], $t];
            }
            while (in_array($alias, $this->aliasCheck) || is_numeric($alias)) {
                $alias = $this->token->generate(1, $tbl);
            }
            array_push($this->aliasCheck, $alias);

            $this->alias = $alias;
            $this->tableAlias[$t] = $alias;
        }
        return [$alias, $t];
    }

    protected function add(array $args, string $method) : void
    {
        $args = $this->helper->normalizeFields($args);
        list($tbl, $selectors, $meth, $bMeth) = $args;
        list($alias, $tbl) = $this->tableAlias($tbl ?? $this->currentTable);
        $args = [
            'tbl' => $tbl,
            'alias' => $alias,
            'data' => $selectors,
            'method' => $meth ?? $method,
            'baseMethod' => $method,
        ];
        $args = $this->params($args, $method);
        list($this->params, $this->method, $baseMethod) = $args;
        $this->{$method}->add($this->stmtFactory->createParameters($method, $this->params, $this->method, $baseMethod, $this->queryType->name));
        $this->lastMethod = $baseMethod;
    }

    protected function addStatement(array $args, string $method)
    {
        list($this->params, $this->method, $baseMethod) = $this->params($args, $method);
        $this->{$method}->add($this->stmtFactory->getStatementObj($method, $this->method, $baseMethod, $this->query));
        $this->{$method}->getChildren()->last()->add(
            $this->stmtFactory->createParameters($method, $this->params, $this->method, $baseMethod, $this->queryType->name)
        );

        $this->lastMethod = $baseMethod;
    }

    protected function addCondition(array $args, string $method) : void
    {
        $tbl = $method == 'on' ? $this->joinTable : $this->currentTable;
        $args = $this->params($args, $method);
        list($this->params, $this->method, $baseMethod) = $args;
        $statement = $this->statement($method);
        if ($this->queryType->name !== 'INSERT') {
            $this->params = $this->helper->normalize($this->params, $this->method);
            $this->params = $this->parseFields($this->params, $this->method);
        }
        /** @var AbstractQueryStatement */
        $stmtParent = $method == 'on' ? $this->{$statement}->getChildren()->last() : $this->query;
        $statementObj = $method !== 'values' ? $this->stmtFactory->getStatementObj($method, $this->method, $baseMethod, $stmtParent) : $this->{$statement};
        $this->addParameters($statementObj, $tbl, $statement, $method, $baseMethod);
        $method == 'on' ? $this->{$statement}->getChildren()->last()->add($statementObj) : ($method !== 'values' ? $this->{$statement}->add($statementObj) : '');
        $this->lastMethod = $baseMethod;
    }

    protected function joinnedRuleKey() : int
    {
        $keyRule = '';
        foreach ($this->queryParams['joinRules']['tables'] as $key => $onRules) {
            if ($this->joinTable === $onRules['table']) {
                $keyRule = $key;
                break;
            }
        }
        return (int) $keyRule;
    }

    protected function getTblAlias(string $table) : string|bool
    {
        foreach ($this->tableAlias as $tbl => $alias) {
            if ($table == $tbl) {
                return $alias;
            }
        }
        return false;
    }

    protected function parseFields(array $conditions = [], ?string $method = null) : array
    {
        // if (in_array($this->queryType->value, ['insert', 'update'])) {
        //     return $conditions;
        // }
        $cond = [];
        if ($method !== null && str_ends_with($method, 'In')) {
            $inArgs = $conditions;
            $conditions = [$inArgs['field']];
        }
        foreach ($conditions as $key => $condition) {
            if (is_array($condition)) {
                $cond[] = $this->parseFields($condition, $method);
            } else {
                if (is_string($condition)) {
                    $parts = explode('|', $condition);
                    if (count($parts) == 2) {
                        $alias = '';
                        $field = '';
                        $alias = $this->getTblAlias($parts[0]);
                        $alias = (! $alias ? $parts[0] : $alias) . (count($parts) > 1 ? '.' : '');
                        $field = strval($parts[1]);
                    } elseif (count($parts) == 1) {
                        $tables = array_keys($this->tableAlias);
                        $searchTbl = str_contains($this->method, 'on') ? $this->joinTable : $this->currentTable;
                        $keytable = array_search($searchTbl, $tables);
                        $rang = $keytable == 0 ? $keytable : $keytable - (count($conditions) - $key) + 1;
                        $alias = str_contains($this->method, 'where') && $key == 1 ? '' : $this->getTblAlias($tables[$rang]) . '.';
                        if ($this->queryType->name != 'SELECT') {
                            $alias = '';
                        }
                        $field = is_string($condition) ? strval($condition) : $condition;
                    }
                    $cond[] = $alias . $field;
                } else {
                    $cond[$key] = $condition;
                }
            }
        }
        if ($method !== null && str_ends_with($method, 'In')) {
            $cond['field'] = $cond;
            $cond['list'] = $inArgs['list'];
            unset($cond[0]);
            $cond = [$cond];
        }
        return $cond;
    }

    protected function selfInstance(AbstractQueryStatement $statementObj, string $tbl, string $statement) : QueryParams
    {
        $this->fromStatus = $this->fromStatus ?? false;
        /** @var self */
        $queryParams = new QueryParams($this->query, $this->helper, $this->token, $this->stmtFactory);
        $queryParams->setCurrentTable($tbl);
        $queryParams->setAlias($this->alias);
        $queryParams->setTableAlias($this->tableAlias);
        $queryParams->setSelectStatus($this->selectStatus);
        $queryParams->setFromStatus($this->fromStatus);
        $queryParams->{'set' . ucfirst($statement)}($statementObj);
        $queryParams->setQueryType($this->queryType);
        return $queryParams;
    }

    private function addParameters(AbstractQueryStatement $statementObj, string $tbl, string $statement, string $method, string $baseMethod) : void
    {
        $results = [];
        foreach ($this->params as $key => $condition) {
            if ($condition instanceof Closure) {
                $queryParams = $this->selfInstance($statementObj, $tbl, $statement);
                call_user_func($condition, $queryParams);
            } elseif (is_array($condition)) {
                $statementObj->add(
                    $this->stmtFactory->createParameters(
                        $method,
                        [
                            'data' => $condition,
                            'tbl' => $tbl,
                            'alias' => $this->alias,
                        ],
                        $this->method,
                        $baseMethod,
                        $this->queryType->name
                    )
                );
            } else {
                $results[$key] = $condition;
                if ($key == array_key_last($this->params)) {
                    $statementObj->add($this->stmtFactory->createParameters(
                        $method,
                        [
                            'data' => $results,
                            'tbl' => $tbl,
                            'alias' => $this->alias,
                        ],
                        $this->method,
                        $baseMethod,
                        $this->queryType->name
                    ));
                }
            }
        }
    }

    private function statement(string $method) : string
    {
        $statement = $method;
        if (isset($this->lastMethod) && str_contains($this->lastMethod, 'join')) {
            if ($this->method !== 'on') {
                throw new BadQuerySyntaxExceptionException('Joined Tables must have ON Conditions');
            } else {
                $statement = 'join';
            }
        }
        return $statement;
    }

    private function params(array $args = [], ?string $method = null) : array
    {
        $params = $args;
        if (array_key_exists('method', $params) && array_key_exists('baseMethod', $params)) {
            $method = $params['method'];
            $bMethod = $params['baseMethod'];
        } else {
            $bMethod = $method;
            if (isset($args[1]) && in_array($args[1], ['orWhere', 'andWhere', 'whereNotIn', 'havingNotIn', 'whereIn'])) {
                $params = $args[0];
                $method = $args[1];
            }
        }
        if (! isset($this->{$bMethod})) {
            $this->{$bMethod} = StatementFactory::create($method, $bMethod, $this->queryType->name, $this->query);
        }
        if (empty($params)) {
            throw new BadQueryArgumentException('You Must have at least one condition');
        }
        return [$params, $method, $bMethod];
    }
}