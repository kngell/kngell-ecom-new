<?php

declare(strict_types=1);

class UnitsEntity extends Entity
{
    /** @id */
    private int $unId;
    /** @title */
    private string $unit;
    private string $descr;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;
    private string $status;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of unId.
     */
    public function getUnId(): int
    {
        return $this->unId;
    }

    /**
     * Set the value of unId.
     */
    public function setUnId(int $unId): self
    {
        $this->unId = $unId;

        return $this;
    }

    /**
     * Get the value of unit.
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * Set the value of unit.
     */
    public function setUnit(string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get the value of descr.
     */
    public function getDescr(): string
    {
        return $this->descr;
    }

    /**
     * Set the value of descr.
     */
    public function setDescr(string $descr): self
    {
        $this->descr = $descr;

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
}