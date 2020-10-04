<?php
namespace App\Entities;

use DateTime;

/**
 * Class ExchangeRateEntity
 */
class Rate
{
    /** @var array */
    private $rates;

    /** @var string */
    private $baseCurrency;

    /** @var DateTime */
    private $date;

    /**
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

    /**
     * @param array $rates
     * @return Rate
     */
    public function setRates(array $rates): Rate
    {
        $this->rates = $rates;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }

    /**
     * @param string $baseCurrency
     * @return Rate
     */
    public function setBaseCurrency(string $baseCurrency): Rate
    {
        $this->baseCurrency = $baseCurrency;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Rate
     */
    public function setDate(DateTime $date): Rate
    {
        $this->date = $date;
        return $this;
    }
}