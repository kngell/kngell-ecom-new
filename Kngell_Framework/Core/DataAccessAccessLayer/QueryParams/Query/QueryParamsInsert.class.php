<?php

declare(strict_types=1);

class QueryParamsInsert extends AbstractQueryParamsNew implements QueryParamsInsertInterface
{
    private array $values = [];

    public function exec(): CollectionInterface
    {
        if (empty($this->fields) && empty($this->values)) {
            throw new BadQueryArgumentException('There is nothing to insert');
        }
        if (count($this->fields) != count($this->values[0])) {
            throw new BadQueryArgumentException('The number of fields must be equals to the number of values');
        }
        $params = [];
        foreach ($this->fields as $key => $field) {
            $parts = explode('.', $field);
            $params[] = ':' . $parts[1];
        }
        $this->queryParams['insert']['fields'] = $this->fields;
        $this->queryParams['insert']['values'] = $this->values;
        $this->queryParams['insert']['params'] = $params;
        if (isset($this->wereConditionObj) && $this->wereConditionObj->getChildrenStorage()->count() > 0) {
            $this->queryParams['insert']['conditions'] = $this->wereConditionObj;
        }
        return new Collection($this->queryParams);
    }

    public function fields(...$fields) : self
    {
        $this->fields = ArrayUtil::flatten_with_keys($this->parseFields($fields));
        return $this;
    }

    public function into(string $tbl, ...$params): self
    {
        if (! isset($this->currentTable)) {
            $this->currentTable = $tbl;
            $this->queryParams['insert']['table'] = $tbl;
        }
        return $this;
    }

    public function values(...$values): self
    {
        $this->values = $values;
        return $this;
    }
}