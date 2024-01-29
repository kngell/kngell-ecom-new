<?php

declare(strict_types=1);

class SelectStatement extends AbstractQueryStatement
{
    protected string $statement = 'SELECT ';

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $r = '';
            foreach ($childs as $key => $child) {
                $sep = $key == array_key_last($childs) ? '' : ', ';
                $selector = $child->proceed();
                $r .= implode(',', $selector[0]) . $sep;
            }
        }
        return [[$this->statement . $this->statement($r)], [], []];
    }
}