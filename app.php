<?php
require_once("src/CommissionComputingService.php");
require_once("src/Entities/Rate.php");
require_once("src/Entities/BinCountry.php");
require_once("src/Entities/Transaction.php");

$dataString = file_get_contents($argv[1]);

if (!$dataString) {
    throw new \Exception("file is empty");
}

$transactions = explode("\n", $dataString);
$ratesData = null;

try {
    $ratesData = file_get_contents('https://api.exchangeratesapi.io/latest');
} catch (\Exception $e) {
    echo $e->getMessage();
}


$rates = json_decode($ratesData, true);

if (!$rates || $rates && !array_key_exists('rates', $rates)) {
    throw new \Exception("Could not get rates!");
}

$commissionCompute = new \App\CommissionComputingService();
$commissionCompute->setRate(getRate($rates));

foreach ($transactions as $transaction) {
    $transaction = json_decode($transaction, true);
    if (!validateTransaction($transaction)) continue;
    $transactionEntity = getTransaction($transaction);
    try {
        $binResults = file_get_contents('https://lookup.binlist.net/' . $transactionEntity->getBin());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

    $binResults = json_decode($binResults, true);
    if (!validateBin($binResults)) continue;
    $commissionCompute->setBinCountry(getBinCountry($binResults));
    echo $commissionCompute->compute($transactionEntity) . "\n";
}

function validateTransaction(?array $row): bool
{
    if (!$row || !array_key_exists('amount', $row) || !array_key_exists('currency', $row)) {
        return false;
    }
    return true;
}

function validateBin(?array $arr)
{
    if (!$arr || !array_key_exists('country', $arr) || !array_key_exists('alpha2', $arr['country'])) {
        return false;
    }
    return true;
}

function getTransaction(array $transaction): \App\Entities\Transaction
{
    $transactionEntity = new \App\Entities\Transaction();
    $transactionEntity->setAmount($transaction['amount']);
    $transactionEntity->setCurrency($transaction['currency']);
    $transactionEntity->setBin($transaction['bin']);
    return $transactionEntity;
}

function getRate(array $rateArr): \App\Entities\Rate
{
    $rate = new \App\Entities\Rate();
    $rate->setDate(new DateTime($rateArr['date']));
    $rate->setBaseCurrency($rateArr['base']);
    $rate->setRates($rateArr['rates']);
    return $rate;
}

function getBinCountry(array $binResults): \App\Entities\BinCountry
{
    $binCountry = new \App\Entities\BinCountry();
    $binCountry->setAlpha2($binResults['country']['alpha2']);
    return $binCountry;
}
