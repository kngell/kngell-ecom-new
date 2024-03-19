<?php

declare(strict_types=1);

class VisitorsEntity extends Entity
{
    /** @id */
    private ?int $vId;
    /** @Hits */
    private ?int $hits;
    /** @Ip Address @var string */
    private ?string $asn;
    private ?string $cityName;
    private ?string $country;
    private ?string $continentCode;
    private ?string $countryName;
    private ?string $countryArea;
    private ?string $countryCallingCode;
    private ?string $countryCapital;
    private ?string $countryCode;
    private ?string $countryCodeIso3;
    private ?string $countryPopulation;
    private ?string $currency;
    private ?string $currencyName;
    private ?string $inEu;
    private ?string $ipAddress;
    private ?string $languages;
    private ?string $latitude;
    private ?string $longitude;
    private ?string $network;
    private ?string $org;
    private ?string $postalCode;
    private ?string $region;
    private ?string $regionCode;
    private ?string $timezone;
    private ?string $utcOffset;
    private ?string $ipVersion;
    private ?string $updatedAt;
    private ?string $createdAt;
    private ?string $cookies;
    private ?string $useragent;

    public function __construct()
    {
        $this->updatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', (new DateTime())->format('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
    }

    /**
     * Get the value of vId.
     */
    public function getVId(): ?int
    {
        return $this->vId;
    }

    /**
     * Set the value of vId.
     */
    public function setVId(?int $vId): self
    {
        $this->vId = $vId;

        return $this;
    }

    /**
     * Get the value of hits.
     */
    public function getHits(): ?int
    {
        return $this->hits;
    }

    /**
     * Set the value of hits.
     */
    public function setHits(?int $hits): self
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Get the value of asn.
     */
    public function getAsn(): ?string
    {
        return $this->asn;
    }

    /**
     * Set the value of asn.
     */
    public function setAsn(?string $asn): self
    {
        $this->asn = $asn;

        return $this;
    }

    /**
     * Get the value of cityName.
     */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /**
     * Set the value of cityName.
     */
    public function setCityName(?string $cityName): self
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get the value of continentCode.
     */
    public function getContinentCode(): ?string
    {
        return $this->continentCode;
    }

    /**
     * Set the value of continentCode.
     */
    public function setContinentCode(?string $continentCode): self
    {
        $this->continentCode = $continentCode;

        return $this;
    }

    /**
     * Get the value of countryArea.
     */
    public function getCountryArea(): ?string
    {
        return $this->countryArea;
    }

    /**
     * Set the value of countryArea.
     */
    public function setCountryArea(?string $countryArea): self
    {
        $this->countryArea = $countryArea;

        return $this;
    }

    /**
     * Get the value of countryCallingCode.
     */
    public function getCountryCallingCode(): ?string
    {
        return $this->countryCallingCode;
    }

    /**
     * Set the value of countryCallingCode.
     */
    public function setCountryCallingCode(?string $countryCallingCode): self
    {
        $this->countryCallingCode = $countryCallingCode;

        return $this;
    }

    /**
     * Get the value of countryCapital.
     */
    public function getCountryCapital(): ?string
    {
        return $this->countryCapital;
    }

    /**
     * Set the value of countryCapital.
     */
    public function setCountryCapital(?string $countryCapital): self
    {
        $this->countryCapital = $countryCapital;

        return $this;
    }

    /**
     * Get the value of countryCode.
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Set the value of countryCode.
     */
    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get the value of countryCodeIso3.
     */
    public function getCountryCodeIso3(): ?string
    {
        return $this->countryCodeIso3;
    }

    /**
     * Set the value of countryCodeIso3.
     */
    public function setCountryCodeIso3(?string $countryCodeIso3): self
    {
        $this->countryCodeIso3 = $countryCodeIso3;

        return $this;
    }

    /**
     * Get the value of countryName.
     */
    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /**
     * Set the value of countryName.
     */
    public function setCountryName(?string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get the value of countryPopulation.
     */
    public function getCountryPopulation(): ?string
    {
        return $this->countryPopulation;
    }

    /**
     * Set the value of countryPopulation.
     */
    public function setCountryPopulation(?string $countryPopulation): self
    {
        $this->countryPopulation = $countryPopulation;

        return $this;
    }

    /**
     * Get the value of currency.
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Set the value of currency.
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of currencyName.
     */
    public function getCurrencyName(): ?string
    {
        return $this->currencyName;
    }

    /**
     * Set the value of currencyName.
     */
    public function setCurrencyName(?string $currencyName): self
    {
        $this->currencyName = $currencyName;

        return $this;
    }

    /**
     * Get the value of inEu.
     */
    public function getInEu(): ?string
    {
        return $this->inEu;
    }

    /**
     * Set the value of inEu.
     */
    public function setInEu(?string $inEu): self
    {
        $this->inEu = $inEu;

        return $this;
    }

    /**
     * Get the value of ipAddress.
     */
    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * Set the value of ipAddress.
     */
    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get the value of languages.
     */
    public function getLanguages(): ?string
    {
        return $this->languages;
    }

    /**
     * Set the value of languages.
     */
    public function setLanguages(?string $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get the value of latitude.
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * Set the value of latitude.
     */
    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of longitude.
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude.
     */
    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get the value of network.
     */
    public function getNetwork(): ?string
    {
        return $this->network;
    }

    /**
     * Set the value of network.
     */
    public function setNetwork(?string $network): self
    {
        $this->network = $network;

        return $this;
    }

    /**
     * Get the value of org.
     */
    public function getOrg(): ?string
    {
        return $this->org;
    }

    /**
     * Set the value of org.
     */
    public function setOrg(?string $org): self
    {
        $this->org = $org;

        return $this;
    }

    /**
     * Get the value of postalCode.
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Set the value of postalCode.
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get the value of region.
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Set the value of region.
     */
    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get the value of regionCode.
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * Set the value of regionCode.
     */
    public function setRegionCode(?string $regionCode): self
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * Get the value of utcOffset.
     */
    public function getUtcOffset(): ?string
    {
        return $this->utcOffset;
    }

    /**
     * Set the value of utcOffset.
     */
    public function setUtcOffset(?string $utcOffset): self
    {
        $this->utcOffset = $utcOffset;

        return $this;
    }

    /**
     * Get the value of ipVersion.
     */
    public function getIpVersion(): ?string
    {
        return $this->ipVersion;
    }

    /**
     * Set the value of ipVersion.
     */
    public function setIpVersion(?string $ipVersion): self
    {
        $this->ipVersion = $ipVersion;

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

    /**
     * Get the value of createdAt.
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt.
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of cookies.
     */
    public function getCookies(): ?string
    {
        return $this->cookies;
    }

    /**
     * Set the value of cookies.
     */
    public function setCookies(?string $cookies): self
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * Get the value of useragent.
     */
    public function getUseragent(): ?string
    {
        return $this->useragent;
    }

    /**
     * Set the value of useragent.
     */
    public function setUseragent(?string $useragent): self
    {
        $this->useragent = $useragent;

        return $this;
    }

    /**
     * Get the value of country.
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set the value of country.
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of timezone.
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * Set the value of timezone.
     */
    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }
}