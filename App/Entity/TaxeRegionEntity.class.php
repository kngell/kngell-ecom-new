<?php

declare(strict_types=1);

class TaxeRegionEntity extends Entity
{
    /** @id */
    private int $trId;
    private ?int $trTaxId;
    private ?int $trCountryCode;
    private ?string $trCountry;
    private ?int $trCatID;
    private ?string $createAt;
    private ?string $updateAt;

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
     * Get the value of trId.
     */
    public function getTrId(): int
    {
        return $this->trId;
    }

    /**
     * Set the value of trId.
     */
    public function setTrId(int $trId): self
    {
        $this->trId = $trId;

        return $this;
    }

    /**
     * Get the value of trTaxId.
     */
    public function getTrTaxId(): ?int
    {
        return $this->trTaxId;
    }

    /**
     * Set the value of trTaxId.
     */
    public function setTrTaxId(?int $trTaxId): self
    {
        $this->trTaxId = $trTaxId;

        return $this;
    }

    /**
     * Get the value of trCountryCode.
     */
    public function getTrCountryCode(): ?int
    {
        return $this->trCountryCode;
    }

    /**
     * Set the value of trCountryCode.
     */
    public function setTrCountryCode(?int $trCountryCode): self
    {
        $this->trCountryCode = $trCountryCode;

        return $this;
    }

    /**
     * Get the value of trCountry.
     */
    public function getTrCountry(): ?string
    {
        return $this->trCountry;
    }

    /**
     * Set the value of trCountry.
     */
    public function setTrCountry(?string $trCountry): self
    {
        $this->trCountry = $trCountry;

        return $this;
    }

    /**
     * Get the value of trCatID.
     */
    public function getTrCatID(): ?int
    {
        return $this->trCatID;
    }

    /**
     * Set the value of trCatID.
     */
    public function setTrCatID(?int $trCatID): self
    {
        $this->trCatID = $trCatID;

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
}