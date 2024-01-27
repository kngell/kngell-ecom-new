<?php

declare(strict_types=1);

class ConditionStatement extends AbstractQueryStatement
{
    private string $statement = '';

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
    }

    public function proceed(?self $conditionObj = null): array
    {
        $childs = $this->children->all();
        $this->braceOpen = count($childs) > 1 ? '(' : '';
        $this->braceClose = count($childs) > 1 ? ')' : '';
        $r = '';
        $parametters = [];
        $bindArray = [];
        foreach ($childs as $key => $child) {
            $nextChild = next($childs);
            list($condition, $params, $bindArr) = $child->proceed();
            $link = count($childs) > 1 && $nextChild ? $nextChild->link() : '';
            $r .= $condition . $link;
            $parametters[] = ! empty($params) ? $params : [];
            $bindArray[] = $bindArr;
        }
        return [$this->braceOpen . $r . $this->braceClose, $parametters, $bindArray];
    }
}