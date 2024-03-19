<?php

declare(strict_types=1);
trait ModelTrait
{
    public function find() : self
    {
        /** @var CollectionInterface */
        $query = $this->queryParams->get();
        if ($query->count() > 0) {
            $options = $query->offsetGet('options');
            if (isset($options['return_mode']) && $options['return_mode'] == 'class' && ! isset($options['class'])) {
                $options = array_merge($options, ['class' => $this->entity::class]);
                $query->offsetSet('options', $options);
            }
        }
        $results = $this->repository()->findBy();
        $this->setAllReturnedValues($results);
        $results = null;
        return $this;
    }

    public function setResults(mixed $results) : void
    {
        $this->_results = $results;
    }

    public function setCount(int $count) : void
    {
        $this->_count = $count;
    }

    public function setStatement(PDOStatement $_statement): self
    {
        $this->_statement = $_statement;
        return $this;
    }

    public function setCon(DatabaseConnexionInterface $_con): self
    {
        $this->_con = $_con;
        return $this;
    }

    public function get_results() : mixed
    {
        return isset($this->_results) ? $this->_results : [];
    }

    protected function setAllReturnedValues(DataMapperInterface $results) : void
    {
        $this->setResults($results->count() > 0 ? $results->get_results() : null);
        $this->setCount($results->count() > 0 ? $results->count() : 0);
        $this->setStatement($results->getQuery());
        $this->setCon($results->getCon());
    }
}