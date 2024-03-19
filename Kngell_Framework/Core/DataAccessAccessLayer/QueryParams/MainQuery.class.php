<?php

declare(strict_types=1);

class MainQuery extends AbstractQueryStatement
{
    public function __construct(string|Closure|null $method = null, ?string $baseMethod = null)
    {
        parent::__construct($method, $baseMethod);
    }

    public function proceed(): array
    {
        if ($this->method instanceof Closure) {
            $r = call_user_func($this->method, []);
            $this->add($r->getQuery());
        }

        $childs = $this->children->all();
        foreach ($childs as $child) {
            $this->tablesSet($child);
            [$query,$params,$bind_arr] = $child->proceed();
            $this->tablesGet($child);
            $this->query .= is_array($query) ? $query[0] : $query;
            $this->parameters[] = $params;
            $this->bind_arr[] = $bind_arr;
        }

        return [
            $this->query,
            ArrayUtil::flatten_with_keys($this->parameters),
            ArrayUtil::flatten_with_keys($this->bind_arr),
        ];
    }
}