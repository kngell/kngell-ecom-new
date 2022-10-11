<?php

declare(strict_types=1);

class WarehouseEntity extends Entity
{
    /** @id */
    private int $whId;
    private string $whName;
    private string $whDescr;
    private string $status;
    private string $company;
    private string $country_code;
    private int $deleted;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of whId.
     */
    public function getWhId(): int
    {
        return $this->whId;
    }

    /**
     * Set the value of whId.
     */
    public function setWhId(int $whId): self
    {
        $this->whId = $whId;

        return $this;
    }

    /**
     * Get the value of whName.
     */
    public function getWhName(): string
    {
        return $this->whName;
    }

    /**
     * Set the value of whName.
     */
    public function setWhName(string $whName): self
    {
        $this->whName = $whName;

        return $this;
    }

    /**
     * Get the value of whDescr.
     */
    public function getWhDescr(): string
    {
        return $this->whDescr;
    }

    /**
     * Set the value of whDescr.
     */
    public function setWhDescr(string $whDescr): self
    {
        $this->whDescr = $whDescr;

        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of company.
     */
    public function getCompany(): string
    {
        return $this->company;
    }

    /**
     * Set the value of company.
     */
    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get the value of country_code.
     */
    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    /**
     * Set the value of country_code.
     */
    public function setCountryCode(string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    /**
     * Get the value of deleted.
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

    /**
     * Set the value of deleted.
     */
    public function setDeleted(int $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}