<?php

declare(strict_types=1);

class GroupUserEntity extends Entity
{
    /** @id */
    private int $gruId;
    /** @userID */
    private int $userId;
    private int $groupId;
    /** @var ?string */
    private ?string $dateEnreg;
    /** @var ?string */
    private ?string $updatedAt;

    public function __construct()
    {
        // $this->dateEnreg = !isset($this->dateEnreg) ? new DateTimeImmutable() : $this->dateEnreg;
    }

    /**
     * Get the value of gruID.
     */
    public function getGruId()
    {
        return $this->gruId;
    }

    /**
     * Get the value of userID.
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userID.
     *
     * @return  self
     */
    public function setUserId($userID)
    {
        $this->userId = $userID;

        return $this;
    }

    /**
     * Get the value of groupID.
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set the value of groupID.
     *
     * @return  self
     */
    public function setGroupId($groupID)
    {
        $this->groupId = $groupID;

        return $this;
    }

    public function delete(?string $field = null) : self
    {
        unset($this->$field);

        return $this;
    }

    /**
     * Get the value of dateEnreg.
     */
    public function getDateEnreg(): ?string
    {
        return $this->dateEnreg;
    }

    /**
     * Set the value of dateEnreg.
     *
     * @return  self
     */
    public function setDateEnreg(?string $dateEnreg) : self
    {
        $this->dateEnreg = $dateEnreg;

        return $this;
    }

    /**
     * Get the value of updatedAt.
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt.
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
