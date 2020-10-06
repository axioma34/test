<?php
namespace App;

class RateLoader extends Provider
{
    /**
     * @return Entities\Rate|null
     */
    public function getRatesDate(): ?\App\Entities\Rate
    {
        $data = parent::makeRequest('https://api.exchangeratesapi.io/latest');
        $rates = json_decode($data, true);
        if (!$this->validateRates($rates)) return $rates;
        $rate = new \App\Entities\Rate();
        $rate->setRates($rates['rates']);
        return $rate;
    }

    protected function validateRates(?array $rates)
    {
        if (!$rates || $rates && !array_key_exists('rates', $rates)) {
            return false;
        }
        return true;
    }
}
