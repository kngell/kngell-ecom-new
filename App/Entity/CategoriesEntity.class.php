<?php

declare(strict_types=1);

class CategoriesEntity extends Entity
{
    /** @id */
    private int $catId;
    private string $categorie;
    private string $description;
    /** @media */
    private string $media;
    private int $parent_id;
    private int $brId;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;
    private string $status;
    private int $deleted;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
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
     * Get the value of categorie.
     */
    public function getCategorie(): string
    {
        return $this->categorie;
    }

    /**
     * Set the value of categorie.
     */
    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get the value of description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of media.
     */
    public function getMedia(): string
    {
        return $this->media;
    }

    /**
     * Set the value of media.
     */
    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get the value of parent_id.
     */
    public function getParentId(): int
    {
        return $this->parent_id;
    }

    /**
     * Set the value of parent_id.
     */
    public function setParentId(int $parent_id): self
    {
        $this->parent_id = $parent_id;

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