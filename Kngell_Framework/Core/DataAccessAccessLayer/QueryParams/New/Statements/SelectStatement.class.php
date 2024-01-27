<?php

declare(strict_types=1);

class SelectStatement extends AbstractQueryStatement
{
    private string $statement = 'SELECT ';

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
                $r .= implode(',', $child->proceed()) . $sep;
            }
        }
        return [$r];
    }
}