<?php

declare(strict_types=1);
abstract class AbstractCrud implements CrudInterface
{
    protected ?QueryParamsInterface $queryParams;

    public function __construct(
        protected ?DataMapperInterface $dataMapper = null,
        ?QueryParamsInterface $queryParams = null,
    ) {
        $this->dataMapper = $dataMapper;
        $this->queryParams = $queryParams;
    }

    public function flushDb($method) : DataMapperInterface
    {
        try {
            [$query,$params,$bindArr] = $this->queryParams->getQuery()->proceed();
            $this->dataMapper->setBindArr($bindArr);
            $this->dataMapper->persist($query, $this->dataMapper->buildQueryParameters(
                $params
            ));
            if ($this->dataMapper->numrow() > 0) {
                return $this->dataMapper->results($this->queryParams->getQueryOptions(), $method);
            }
            return $this->dataMapper;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     *@inheritDoc
     */
    public function lastID(): int
    {
        return $this->dataMapper->getLasID();
    }
}