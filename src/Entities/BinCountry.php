<?php

namespace App\Entities;

/**
 * Class CountryEntity
 */
class BinCountry
{
    /** @var string */
    private $numeric;

    /** @var string */
    private $alpha2;

    /** @var string */
    private $name;

    /** @var string */
    private $emoji;

    /** @var string */
    private $currency;

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    /**
     * @return string
     */
    public function getNumeric(): string
    {
        return $this->numeric;
    }

    /**
     * @param string $numeric
     * @return BinCountry
     */
    public function setNumeric(string $numeric): BinCountry
    {
        $this->numeric = $numeric;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @param string $alpha2
     * @return BinCountry
     */
    public function setAlpha2(string $alpha2): BinCountry
    {
        $this->alpha2 = $alpha2;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return BinCountry
     */
    public function setName(string $name): BinCountry
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmoji(): string
    {
        return $this->emoji;
    }

    /**
     * @param string $emoji
     * @return BinCountry
     */
    public function setEmoji(string $emoji): BinCountry
    {
        $this->emoji = $emoji;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return BinCountry
     */
    public function setCurrency(string $currency): BinCountry
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return BinCountry
     */
    public function setLatitude(float $latitude): BinCountry
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return BinCountry
     */
    public function setLongitude(float $longitude): BinCountry
    {
        $this->longitude = $longitude;
        return $this;
    }
}