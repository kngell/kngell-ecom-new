<?php

declare(strict_types=1);

class WarehouseProductEntity extends Entity
{
    /** @id */
    private int $whpId;
    private int $productId;
    private int $whId;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of whpId.
     */
    public function getWhpId(): int
    {
        return $this->whpId;
    }

    /**
     * Set the value of whpId.
     */
    public function setWhpId(int $whpId): self
    {
        $this->whpId = $whpId;

        return $this;
    }

    /**
     * Get the value of productId.
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * Set the value of productId.
     */
    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
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