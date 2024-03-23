<?php

declare(strict_types=1);

class StatementFactory
{
    public static function create(string $method, string $baseMethod, string $queryType, AbstractQueryStatement $parent) : AbstractQueryStatement
    {
        $statement = StatementType::get($baseMethod)->name;
        if (null !== $statement) {
            /** @var AbstractQueryStatement */
            $statement = new $statement($method, $baseMethod, $queryType);
            $statement->getLevel() == false ? $statement->setLevel($parent->getLevel() + 1) : '';
            return $statement;
        }
    }

    public function getStatementObj(?string $method = null, ?string $baseMethod = null, ?AbstractQueryStatement $parent = null) : AbstractQueryStatement
    {
        $statement = StatementType::get($baseMethod)->name;
        $obj = new $statement($method, $baseMethod);
        null !== $parent ? $obj->setLevel($parent->getLevel() + 1) : '';
        return $obj;
    }

    public function createParameters(array $params, string $method, string $basemethod, string $queryType) : AbstractQueryStatement
    {
        $statement = StatementType::get($basemethod);
        $statementParams = $statement->parameters($statement->value);
        if (null !== $statementParams) {
            return new $statementParams($params, $method, $basemethod, $queryType);
        }
    }
}