<?php

declare(strict_types=1);

class StatementFactory
{
    public function __construct(private QueryParamsHelper $helper)
    {
    }

    public function createStatement(string $method) : AbstractQueryStatement
    {
        $statement = null;
        switch (true) {
            case str_contains(strtolower($method), 'select'):
                $statement = SelectStatement::class;
                break;
            case in_array(strtolower($method), ['where', 'on', 'having']):
                $statement = ConditionStatement::class;
                break;
            case in_array(strtolower($method), ['groupby', 'orderby']):
                $statement = GroupAndSortStatement::class;
                break;
            case in_array(strtolower($method), ['limit', 'offset']):
                $statement = CounterStatement::class;
                break;
            case in_array(strtolower($method), ['join', 'from', 'table']):
                $statement = TableStatement::class;
                break;
            default:
                throw new BadQueryArgumentException('Bad SQL request! please revisit your request');
                break;
        }
        if (null !== $statement) {
            /** @var AbstractQueryStatement */
            $statement = new $statement(new Collection(), $this->helper, $method);
            $statement->getLevel() == false ? $statement->setLevel(0) : '';
            return $statement;
        }
    }

    public function getStatementObj(?string $statementString, ?string $method = null, ?AbstractQueryStatement $parent = null) : AbstractQueryStatement
    {
        if (class_exists($statementString)) {
            $obj = new $statementString(new Collection, $this->helper, $method);
            null !== $parent ? $obj->setLevel($parent->getLevel() + 1) : '';
            return $obj;
        }
    }

    public function createParameters(string $type, $params, $method) : AbstractQueryStatement
    {
        $statementParams = null;
        switch (true) {
            case str_contains(strtolower($type), 'select'):
                $statementParams = SelectParameters::class;
                break;
            case in_array(strtolower($type), ['where', 'on', 'having']):
                $statementParams = ConditionParameters::class;
                break;
            case in_array(strtolower($type), ['groupby', 'orderby']):
                $statementParams = GroupAndSortParameters::class;
                break;
            case in_array(strtolower($type), ['limit', 'offset']):
                $statementParams = CounterParameters::class;
                break;
            case in_array(strtolower($type), ['join', 'from', 'table']):
                $statementParams = TableParameters::class;
                break;
            default:
                throw new BadQueryArgumentException('Bad SQL request! please revisit your request');
                break;
        }
        if (null !== $statementParams) {
            return new $statementParams($params, $method, new Collection(), $this->helper);
        }
    }
}