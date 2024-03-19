<?php

declare(strict_types=1);

trait DataMapperGetSetTrait
{
    /**
     *  Get las insert ID
     * ======================================================================.
     *   *
     */
    public function getLasID(): int
    {
        if (isset($this->_lastID)) {
            return $this->_lastID;
        }
    }

    public function count() : int
    {
        return $this->_count;
    }

    public function get_results() : mixed
    {
        return $this->_results;
    }

    public function set_results(mixed $results) : self
    {
        $this->_results = $results;

        return $this;
    }

    public function setLastID(): self
    {
        try {
            if ($this->dbCon->open()) {
                $lastID = $this->dbCon->open()->lastInsertId();
                if (! empty($lastID)) {
                    $this->_lastID = intval($lastID);
                }
            }
            return $this;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get numberof row
     * ============================================================.
     *@inheritDoc
     */
    public function numrow(): int
    {
        if ($this->_query) {
            return $this->_count = $this->_query->rowCount();
        }
    }

    /**
     * Get the value of dbCon.
     */
    public function getCon(): DatabaseConnexionInterface
    {
        return $this->dbCon;
    }

    /**
     * Get the value of _query.
     */
    public function getQuery(): PDOStatement
    {
        return $this->_query;
    }
}