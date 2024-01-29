<?php

declare(strict_types=1);

class MainQuery extends AbstractQueryStatement
{
    private string $query = '';
    private array $params = [];
    private array $bind_arr = [];

    public function __construct(?CollectionInterface $children = null, ?QueryParamsHelper $helper = null, ?string $method = null)
    {
        parent::__construct($children, $helper, $method);
    }

    public function proceed(): array
    {
        if ($this->children->count() > 0) {
            $childs = $this->children->all();
            foreach ($childs as $child) {
                [$query,$params,$bind_arr] = $child->proceed();
                $this->query .= is_array($query) ? $query[0] : $query;
                $this->params[] = $params;
                $this->bind_arr[] = $bind_arr;
            }
        }
        return [
            $this->query,
            ArrayUtil::flatten_with_keys($this->params),
            ArrayUtil::flatten_with_keys($this->bind_arr),
        ];
    }

    //  public function add(AbstractQueryStatement $obj): AbstractQueryStatement
    //  {
    //      $update = false;
    //      foreach ($this->children as $child) {
    //          if ($child::class === $obj::class && $child->getMethod() === $obj->getMethod()) {
    //              $this->children->updateValue($child, $obj);
    //              $update = true;
    //          }
    //      }
    //      if (! $update) {
    //          $obj->level = $this->level + 1;
    //          $this->children->add($obj);
    //          $obj->setParent($this);
    //      }
    //      return $this;
    //  }
}