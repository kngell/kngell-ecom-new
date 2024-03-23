<?php

declare(strict_types=1);

abstract class AbstractQueryParams
{
    use QueryParamsGetterAndSettersTrait;

    protected ?QueryType $queryType;
    protected MainQuery $query;
    protected StatementFactory $stmtFactory;
    protected AbstractQueryStatement $select;
    protected AbstractQueryStatement $fields;
    protected AbstractQueryStatement $table;
    protected AbstractQueryStatement $raw;
    protected AbstractQueryStatement $from;
    protected AbstractQueryStatement $join;
    protected AbstractQueryStatement $where;
    protected AbstractQueryStatement $on;
    protected AbstractQueryStatement $having;
    protected AbstractQueryStatement $set;
    protected AbstractQueryStatement $groupBy;
    protected AbstractQueryStatement $orderBy;
    protected AbstractQueryStatement $limit;
    protected AbstractQueryStatement $offset;
    protected AbstractQueryStatement $insert;
    protected AbstractQueryStatement $update;
    protected AbstractQueryStatement $delete;
    protected AbstractQueryStatement $width;
    protected AbstractQueryStatement $values;
    protected AbstractQueryStatement $with;
    protected AbstractQueryStatement $stmtObj;
    protected Entity|CollectionInterface $entity;
    protected QueryParamsHelper $helper;
    protected Closure $queryClosure;
    protected array $queryParams = [];
    protected string $currentTable;
    protected string $alias;
    protected string $method;
    protected string $lastMethod;
    protected string $cteTable = 'cteTable';
    // protected array $tableAlias;
    // protected array $aliasCheck = [];
    protected array|Entity|CollectionInterface $params = [];
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
    protected bool $whereStatus;
    protected bool $dataFromEntities = false;

    public function __construct(MainQuery $query, StatementFactory $stFactory, QueryParamsHelper $helper)
    {
        $this->stmtFactory = $stFactory;
        $this->query = $query;
        $this->query->setLevel(0);
        $this->helper = $helper;
    }

    protected function normalizeFields(array $args) : array
    {
        $args = is_array($args) && isset($args[0]) ? $args[0] : $args;

        if (array_key_exists('tbl', $args)) {
            $tbl = $args['tbl'];
            $selectors = array_key_exists('selectors', $args) ? $args['selectors'] : [];
            array_key_exists('method', $args) ? $method = $args['method'] : '';
            array_key_exists('baseMethod', $args) ? $baseMethod = $args['baseMethod'] : '';
            $selectors = ArrayUtil::flatten_without_keys($selectors);
        }
        return [$tbl ?? null, $selectors ?? [], $method ?? null, $baseMethod ?? null];
    }

    protected function add(array $args, string $method) : void
    {
        $args = $this->normalizeFields($args);
        list($tbl, $selectors, $meth, $bMeth) = $args;
        $args = [
            'tbl' => $tbl ?? $this->currentTable,
            'data' => $selectors,
            'method' => $meth ?? $method,
            'baseMethod' => $method,
        ];
        $args = $this->params($args, $method);
        list($this->params, $this->method, $baseMethod) = $args;
        $this->{$method}->add($this->stmtFactory->createParameters($this->params, $method, $baseMethod, $this->queryType->name));
        $this->lastMethod = $baseMethod;
    }

    protected function addStatement(array $args, string $method)
    {
        list($this->params, $method, $baseMethod) = $this->params($args, $method);
        $this->{$method}->add($this->stmtFactory->getStatementObj($method, $baseMethod, $this->query));
        $this->{$method}->getChildren()->last()->add(
            $this->stmtFactory->createParameters($this->params, $method, $baseMethod, $this->queryType->name)
        );

        $this->lastMethod = $baseMethod;
    }

    protected function addCondition(array $args, string $baseMethod) : void
    {
        $tbl = $baseMethod == 'on' ? $this->joinTable : $this->currentTable;
        $args = $this->params($args, $baseMethod);
        list($this->params, $method, $baseMethod) = $args;
        $statement = $this->statement($baseMethod);
        if ($this->queryType->name !== 'INSERT') {
            $this->params = $this->helper->normalize($this->params, $method);
        }
        /** @var AbstractQueryStatement */
        $stmtParent = $baseMethod == 'on' ? $this->{$statement}->getChildren()->last() : $this->query;

        $statementObj = $this->stmtFactory->getStatementObj($method, $baseMethod, $stmtParent);

        $this->addParameters($statementObj, $tbl, $statement, $method, $baseMethod);
        $baseMethod == 'on' ? $this->{$statement}->getChildren()->last()->add($statementObj) : $this->{$statement}->add($statementObj);
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

    protected function selfInstance(AbstractQueryStatement $statementObj, string $tbl, string $statement) : QueryParams
    {
        $this->fromStatus = $this->fromStatus ?? false;
        /** @var self */
        $queryParams = new QueryParams(new MainQuery(), $this->stmtFactory, $this->helper);
        foreach ($this as $key => $value) {
            if (method_exists($queryParams, 'set' . ucfirst($key)) && ! $this->$key instanceof AbstractQueryStatement) {
                $value = match (true) {
                    $key === $statement => $statementObj,
                    $key == 'currentTable' => $tbl,
                    default => $value,
                };
                $queryParams->{'set' . ucfirst($key)}($value);
            }
        }
        return $queryParams;
    }

    protected function params(array $args = [], ?string $method = null) : array
    {
        $params = $args;
        if (array_key_exists('method', $params) && array_key_exists('baseMethod', $params)) {
            $method = $params['method'];
            $bMethod = $params['baseMethod'];
        } else {
            $bMethod = $method;
            if (isset($args[1]) && in_array($args[1], ['orWhere', 'andWhere', 'whereNotIn', 'havingNotIn', 'whereIn', 'updateCte'])) {
                $params = str_ends_with($args[1], 'In') ? [$args[0]] : $args[0];
                $method = $args[1];
            }
        }
        if (! isset($this->{$bMethod}) & $bMethod !== 'on') {
            $this->{$bMethod} = StatementFactory::create($method, $bMethod, $this->queryType->name, $this->query);
        }
        // if (empty($params)) {
        //     throw new BadQueryArgumentException('You Must have at least one condition');
        // }
        if (isset($params['conditions'])) {
            $params = $params['conditions'];
        }
        return [$params, $method, $bMethod];
    }

    protected function addParameters(AbstractQueryStatement $statementObj, string $tbl, string $statement, string $method, string $baseMethod) : void
    {
        $results = [];
        foreach ($this->params as $key => $condition) {
            if ($condition instanceof Closure) {
                $queryParams = $this->selfInstance($statementObj, $tbl, $statement);
                // $r = call_user_func($condition, $queryParams);
            }

            if (is_array($condition)) {
                $statementObj->add(
                    $this->stmtFactory->createParameters(
                        [
                            'data' => $condition,
                            'tbl' => $tbl,
                            'queryParams' => $queryParams ?? null,
                        ],
                        $method,
                        $baseMethod,
                        $this->queryType->name
                    )
                );
            } else {
                $results[$key] = $condition;
                if ($key == array_key_last($this->params)) {
                    $statementObj->add($this->stmtFactory->createParameters(
                        [
                            'data' => $results,
                            'tbl' => $tbl,
                            'queryParams' => $queryParams ?? null,
                        ],
                        $method,
                        $baseMethod,
                        $this->queryType->name
                    ));
                }
            }
        }
    }

    protected function statement(string $method) : string
    {
        $statement = $method;
        if (isset($this->lastMethod) && str_contains($this->lastMethod, 'join')) {
            if ($method !== 'on') {
                throw new BadQuerySyntaxExceptionException('Joined Tables must have ON Conditions');
            } else {
                $statement = 'join';
            }
        }
        return $statement;
    }
}