<?php

declare(strict_types=1);

abstract class AbstractQueryParamsNew
{
    use QueryParamsTrait;

    protected QueryParamsHelper $helper;
    protected StatementFactory $stmtFactory;
    protected SelectStatement $select;
    protected TableStatement $table;
    protected TableStatement $from;
    protected TableStatement $join;
    protected ConditionStatement $where;
    protected ConditionStatement $on;
    protected ConditionStatement $having;
    protected GroupAndSortStatement $groupBy;
    protected GroupAndSortStatement $orderBy;
    protected CounterStatement $limit;
    protected CounterStatement $offset;
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
    protected array $fields = [];
    protected array $values = [];
    protected string $onRule = 'INNER JOIN';
    protected ?string $joinTable = null;
    protected bool $selectStatus = false;
    protected bool $fromStatus = false;

    public function __construct(QueryParamsHelper $helper, Token $token, StatementFactory $stFactory)
    {
        $this->helper = $helper;
        $this->token = $token;
        $this->stmtFactory = $stFactory;
    }

    protected function inNotinConditions(array $conditions) : array
    {
        if (count($conditions) == 2) {
            return array_merge([$conditions[0]], $conditions[1]);
        }
        throw new BadQueryArgumentException('Bad arguments in where clause, please check!');
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
            while (in_array($alias, $this->aliasCheck) || is_numeric($alias)) {
                $alias = $this->token->generate(1, $tbl);
            }
            array_push($this->aliasCheck, $alias);
            $this->alias = $alias;
            $this->tableAlias[$t] = $alias;
        }
        return [$alias, $t];
    }

    protected function statementProcessing(array $args, string $method, string $stmt)
    {
        list($this->params, $this->method) = $this->params($args, $method);
        if (isset($this->lastMethod) && str_contains($this->lastMethod, 'join')) {
            if ($this->method !== 'on') {
                throw new BadQuerySyntaxExceptionException('JOINED Tables must have ON Conditions');
            }
        }
        if (! isset($this->{$stmt})) {
            $this->{$stmt} = $this->stmtFactory->createStatement($method);
        }
        $statementString = $this->{$stmt}::class;
        $this->{$method}->add($this->stmtFactory->getStatementObj($statementString, $this->method));
        $this->{$method}->getChildren()->last()->add(
            $this->stmtFactory->createParameters($method, $this->params, $this->method)
        );
        $this->lastMethod = $this->method;
    }

    protected function conditionsProcessing(array $args, string $method, string $tbl, string $alias) : void
    {
        $statement = $method;
        list($this->params, $this->method) = $this->params($args, $method);
        if (empty($this->params)) {
            throw new BadQueryArgumentException('You Must have at least one condition');
        }
        if (! isset($this->{$statement})) {
            $this->{$statement} = $this->stmtFactory->createStatement($statement);
        }
        if (isset($this->lastMethod) && str_contains($this->lastMethod, 'join')) {
            if ($this->method !== 'on') {
                throw new BadQuerySyntaxExceptionException('JOINED Tables must have ON Conditions');
            } else {
                $statement = 'join';
            }
        }
        $this->params = $this->helper->normalize($this->params);
        $this->params = $this->parseFields($this->params);
        $results = [];
        /** @var AbstractQueryStatement */
        $stmtParent = $method == 'on' ? $this->{$statement}->getChildren()->last() : $this->{$statement};
        $statementObj = $this->stmtFactory->getStatementObj($this->{$method}::class, $this->method, $stmtParent);

        foreach ($this->params as $key => $condition) {
            if ($condition instanceof Closure) {
                /** @var self */
                $queryParams = new QueryParamsNew($this->helper, $this->token, $this->stmtFactory);
                $queryParams->setCurrentTable($this->currentTable);
                $queryParams->setAlias($alias);
                $queryParams->setTableAlias($this->tableAlias);
                $queryParams->setSelectStatus($this->selectStatus);
                $queryParams->setFromStatus($this->fromStatus);
                $queryParams->{'set' . ucfirst($statement)}($statementObj);
                call_user_func($condition, $queryParams);
            } elseif (is_array($condition)) {
                $statementObj->add(
                    $this->stmtFactory->createParameters(
                        $method,
                        [
                            'condition' => $condition,
                            'table' => $tbl,
                            'tableAlias' => $alias,
                        ],
                        $this->method
                    )
                );
            } else {
                $results[$key] = $condition;
                if ($key == array_key_last($this->params)) {
                    $statementObj->add($this->stmtFactory->createParameters(
                        $method,
                        [
                            'condition' => $results,
                            'table' => $tbl,
                            'tableAlias' => $alias,
                        ],
                        $this->method
                    ));
                }
            }
        }
        $method == 'on' ? $this->{$statement}->getChildren()->last()->add($statementObj) : $this->{$statement}->add($statementObj);
        $this->lastMethod = $this->method;
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

    protected function parseFields(array $conditions) : array
    {
        $cond = [];
        foreach ($conditions as $key => $condition) {
            if (is_array($condition)) {
                $cond[] = $this->parseFields($condition);
            } else {
                if (is_string($condition)) {
                    $parts = explode('|', $condition);
                    if (count($parts) == 2) {
                        $alias = '';
                        $field = '';
                        $alias = $this->getTblAlias($parts[0]);
                        $alias = (! $alias ? $parts[0] : $alias) . (count($parts) > 1 ? '.' : '');
                        $field = $parts[1];
                    } elseif (count($parts) == 1) {
                        $tables = array_keys($this->tableAlias);
                        $searchTbl = str_contains($this->method, 'on') ? $this->joinTable : $this->currentTable;
                        $keytable = array_search($searchTbl, $tables);
                        $rang = $keytable == 0 ? $keytable : $keytable - (count($conditions) - $key) + 1;
                        $alias = str_contains($this->method, 'where') && $key == 1 ? '' : $this->getTblAlias($tables[$rang]) . '.';
                        $field = $condition;
                    }
                    $cond[] = $alias . $field;
                } else {
                    $cond[$key] = $condition;
                }
            }
        }
        return $cond;
    }

    protected function parseFieldsValuesForInsert(array $params) : self
    {
        $paramsForFields = $params[0];
        if (ArrayUtil::isAssoc($paramsForFields)) {
            foreach ($paramsForFields as $field => $value) {
                $this->fields[] = $this->parseFields([$field])[0];
            }
        }
        foreach ($params as $param) {
            $v = [];
            foreach ($param as $key => $value) {
                $v[] = $value;
            }
            $this->values[] = $v;
        }
        return $this;
    }

    private function params(array $args = [], ?string $method = null) : array
    {
        $params = $args;
        if (isset($args[1]) && in_array($args[1], ['orWhere', 'andWhere', 'whereNotIn', 'havingNotIn', 'whereIn'])) {
            $params = $args[0];
            $method = $args[1];
        }
        return [$params, $method];
    }
}