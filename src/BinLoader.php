<?php
namespace App;

class BinLoader extends Provider
{
    /**
     * @param $bin
     * @return Entities\BinCountry|null
     */
    public function getBinDate($bin): ?\App\Entities\BinCountry
    {
        $data = parent::makeRequest('https://lookup.binlist.net/' . $bin);
        $binResults = json_decode($data, true);
        if (!$this->validateBin($binResults)) return null;

        $binCountry = new \App\Entities\BinCountry();
        $binCountry->setAlpha2($binResults['country']['alpha2']);
        return $binCountry;
    }

    protected function validateBin(?array $arr)
    {
        if (!$arr || !array_key_exists('country', $arr) || !array_key_exists('alpha2', $arr['country'])) {
            return false;
        }
        return true;
    }
}
