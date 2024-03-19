<?php

declare(strict_types=1);

class ConditionStatement extends AbstractQueryStatement
{
    protected string $statement;

    public function __construct(?string $method = null, ?string $baseMethod = null, ?string $queryType = null)
    {
        parent::__construct($method, $baseMethod, $queryType);
        $this->statement = ConditionType::get($this->method);
    }

    public function proceed(?self $conditionObj = null): array
    {
        $childs = $this->children->all();
        $this->useBrace();
        foreach ($childs as $key => $child) {
            $nextChild = next($childs);
            $this->tablesSet($child);
            list($condition, $params, $bindArr) = $child->proceed();
            $this->tablesGet($child);
            $link = count($childs) > 1 && $nextChild ? $nextChild->link() : '';
            $this->query .= $condition . $link;
            $this->parameters[] = ! empty($params) ? $params : [];
            $this->bind_arr[] = $bindArr;
            if (str_ends_with($this->query, $link) && $link !== '' && $this->statement != '') {
                $this->statement = '';
            }
        }
        $this->query();

        return [$this->statement . $this->braceOpen . $this->query . $this->braceClose, $this->parameters, $this->bind_arr];
    }

    protected function useBrace(array $childs = []): void
    {
        if (! $this->parent instanceof MainQuery && count($childs) > 1) {
            $this->braceOpen = '(';
            $this->braceClose = ')';
        }
    }

    private function query() : void
    {
        if (str_contains($this->query, $this->statement) && ! empty($this->statement)) {
            $this->query = str_replace($this->statement, '', $this->query);
            $countBrackets = StringUtil::countChar($this->query, '(');
            if (str_contains($this->query, 'WHERE') && $countBrackets > 1) {
                $this->query = trim(trim($this->query, '()'));
            }
        }
        if (str_contains($this->parent->getQuery(), 'WHERE') && str_contains($this->query, 'WHERE')) {
            $this->statement = $this->statementLink();
            $this->query = str_replace('WHERE ', '', $this->query);
        }
    }

    private function statementLink() : string
    {
        return match (true) {
            $this->method == 'where' => ' AND',
            $this->method == 'orWhere' => ' OR',
        };
    }
}