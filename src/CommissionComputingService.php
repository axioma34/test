<?php

namespace App;


use App\Entities\Transaction;
use App\Entities\BinCountry;
use App\Entities\Rate;

class CommissionComputingService
{

    /** @var Rate */
    private $rate;

    /** @var BinCountry */
    private $binCountry;

    /** @var string */
    private $currencyCode;

    /** @var float */
    private $eurCommission;

    /** @var float */
    private $notEurCommission;

    const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct()
    {
        $this->eurCommission = 0.01;
        $this->notEurCommission = 0.02;
        $this->currencyCode = 'EUR';
    }

    protected function ceilPrecision(float $value, int $precision): float
    {
        $coefficient = pow(10, $precision);
        return ceil($value * $coefficient) / $coefficient;
    }

    /**
     * @param Transaction $transaction
     * @return float
     * @throws \Exception
     */
    public function compute (Transaction $transaction) {
        if (!$this->getRate() instanceof Rate) {
            throw new \Exception('Rate was not set!');
        }

        if (!$this->getBinCountry() instanceof  BinCountry) {
            throw new \Exception('BinCountry was not set!');
        }

        $isEu = array_search($this->binCountry->getAlpha2(), self::EU_COUNTRIES);

        $rates = $this->getRate()->getRates();
        $currency = $transaction->getCurrency();
        $rate = array_key_exists($currency, $rates) ? $rates[$currency] : 0;
        $amntFixed = 0;

        if ($currency == $this->getCurrencyCode() || $rate === 0) {
            $amntFixed = $transaction->getAmount();
        }

        if ($currency != $this->getCurrencyCode() && $rate > 0) {
            $amntFixed = $transaction->getAmount() / $rate;
        }

        $amntFixed *= $isEu !== false ? $this->getEurCommission() : $this->getNotEurCommission();
        return $this->ceilPrecision($amntFixed, 2);
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     * @return CommissionComputingService
     */
    public function setCurrencyCode(string $currencyCode): CommissionComputingService
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getEurCommission(): float
    {
        return $this->eurCommission;
    }

    /**
     * @param float $eurCommission
     * @return CommissionComputingService
     */
    public function setEurCommission(float $eurCommission): CommissionComputingService
    {
        $this->eurCommission = $eurCommission;
        return $this;
    }

    /**
     * @return float
     */
    public function getNotEurCommission(): float
    {
        return $this->notEurCommission;
    }

    /**
     * @param float $notEurCommission
     * @return CommissionComputingService
     */
    public function setNotEurCommission(float $notEurCommission): CommissionComputingService
    {
        $this->notEurCommission = $notEurCommission;
        return $this;
    }

    /**
     * @return Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param Rate $rate
     * @return CommissionComputingService
     */
    public function setRate(Rate $rate): CommissionComputingService
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return BinCountry
     */
    public function getBinCountry()
    {
        return $this->binCountry;
    }

    /**
     * @param BinCountry $binCountry
     * @return CommissionComputingService
     */
    public function setBinCountry(BinCountry $binCountry): CommissionComputingService
    {
        $this->binCountry = $binCountry;
        return $this;
    }
}
