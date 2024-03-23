<?php

declare(strict_types=1);

class BrandEntity extends Entity
{
    /** @id */
    private int $brId;
    private ?string $brName;
    private ?string $brDescr;
    private ?string $status;
    private ?string $createAt;
    private ?string $updateAt;
    private int $deleted;

    public function __construct()
    {
        // $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    public function delete(?string $field = null) : self
    {
        if (isset($this->$field)) {
            unset($this->$field);
        }

        return $this;
    }

    /**
     * Get the value of brId.
     */
    public function getBrId(): int
    {
        return $this->brId;
    }

    /**
     * Set the value of brId.
     */
    public function setBrId(int $brId): self
    {
        $this->brId = $brId;

        return $this;
    }

    /**
     * Get the value of brName.
     */
    public function getBrName(): ?string
    {
        return $this->brName;
    }

    /**
     * Set the value of brName.
     */
    public function setBrName(?string $brName): self
    {
        $this->brName = $brName;

        return $this;
    }

    /**
     * Get the value of brDescr.
     */
    public function getBrDescr(): ?string
    {
        return $this->brDescr;
    }

    /**
     * Set the value of brDescr.
     */
    public function setBrDescr(?string $brDescr): self
    {
        $this->brDescr = $brDescr;

        return $this;
    }

    /**
     * Get the value of status.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status.
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of createAt.
     */
    public function getCreateAt(): ?string
    {
        return $this->createAt;
    }

    /**
     * Set the value of createAt.
     */
    public function setCreateAt(?string $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get the value of updateAt.
     */
    public function getUpdateAt(): ?string
    {
        return $this->updateAt;
    }

    /**
     * Set the value of updateAt.
     */
    public function setUpdateAt(?string $updateAt): self
    {
        $this->updateAt = $updateAt;

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
}