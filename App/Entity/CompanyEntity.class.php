<?php

declare(strict_types=1);

class CompanyEntity extends Entity
{
    /** @id */
    private int $compId;
    private string $sigle;
    private string $denomination;
    private string $siret;
    private string $siteWeb;
    private string $rna;
    private string $tva;
    private string $activite;
    private string $phone;
    private string $mobile;
    private string $fax;
    private string $couriel;
    private int $deleted;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;
    private string $status;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }

    /**
     * Get the value of compId.
     */
    public function getCompId(): int
    {
        return $this->compId;
    }

    /**
     * Set the value of compId.
     */
    public function setCompId(int $compId): self
    {
        $this->compId = $compId;

        return $this;
    }

    /**
     * Get the value of sigle.
     */
    public function getSigle(): string
    {
        return $this->sigle;
    }

    /**
     * Set the value of sigle.
     */
    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * Get the value of denomination.
     */
    public function getDenomination(): string
    {
        return $this->denomination;
    }

    /**
     * Set the value of denomination.
     */
    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    /**
     * Get the value of siret.
     */
    public function getSiret(): string
    {
        return $this->siret;
    }

    /**
     * Set the value of siret.
     */
    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get the value of siteWeb.
     */
    public function getSiteWeb(): string
    {
        return $this->siteWeb;
    }

    /**
     * Set the value of siteWeb.
     */
    public function setSiteWeb(string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    /**
     * Get the value of rna.
     */
    public function getRna(): string
    {
        return $this->rna;
    }

    /**
     * Set the value of rna.
     */
    public function setRna(string $rna): self
    {
        $this->rna = $rna;

        return $this;
    }

    /**
     * Get the value of tva.
     */
    public function getTva(): string
    {
        return $this->tva;
    }

    /**
     * Set the value of tva.
     */
    public function setTva(string $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get the value of activite.
     */
    public function getActivite(): string
    {
        return $this->activite;
    }

    /**
     * Set the value of activite.
     */
    public function setActivite(string $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get the value of phone.
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone.
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of mobile.
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * Set the value of mobile.
     */
    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get the value of fax.
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * Set the value of fax.
     */
    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get the value of couriel.
     */
    public function getCouriel(): string
    {
        return $this->couriel;
    }

    /**
     * Set the value of couriel.
     */
    public function setCouriel(string $couriel): self
    {
        $this->couriel = $couriel;

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