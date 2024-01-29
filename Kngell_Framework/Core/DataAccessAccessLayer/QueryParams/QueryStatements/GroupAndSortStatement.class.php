<?php

declare(strict_types=1);

class GroupAndSortStatement extends AbstractQueryStatement
{
    protected string $statement;

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
        $this->statement = match (true) {
            $this->method == 'groupBy' => ' GROUP BY ',
            $this->method == 'orderBy' => ' ORDER BY ',
            default => '',
        };
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            $r = '';
            foreach ($childs as $child) {
                $gs = $child->proceed();
                $r .= implode(',', $gs[0]);
            }
        }
        return [[$this->statement . $this->statement($r)], [], []];
    }
}