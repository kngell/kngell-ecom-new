<?php

declare(strict_types=1);

class ProductCategorieEntity extends Entity
{
    /** @id */
    private int $pcId;
    private int $pdtId;
    private int $catId;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of pcId.
     */
    public function getPcId(): int
    {
        return $this->pcId;
    }

    /**
     * Set the value of pcId.
     */
    public function setPcId(int $pcId): self
    {
        $this->pcId = $pcId;

        return $this;
    }

    /**
     * Get the value of pdtId.
     */
    public function getPdtId(): int
    {
        return $this->pdtId;
    }

    /**
     * Set the value of pdtId.
     */
    public function setPdtId(int $pdtId): self
    {
        $this->pdtId = $pdtId;

        return $this;
    }

    /**
     * Get the value of catId.
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * Set the value of catId.
     */
    public function setCatId(int $catId): self
    {
        $this->catId = $catId;

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