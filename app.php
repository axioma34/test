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

$rate = new \App\Entities\Rate();
$rate->setDate(new DateTime($rates['date']));
$rate->setBaseCurrency($rates['base']);
$rate->setRates($rates['rates']);

$commissionCompute = new \App\CommissionComputingService();
$commissionCompute->setRate($rate);

foreach ($transactions as $transaction) {
    $transaction = json_decode($transaction, true);
    if (!validateTransaction($transaction)) continue;
    $transactionEntity = new \App\Entities\Transaction();
    $transactionEntity->setAmount($transaction['amount']);
    $transactionEntity->setCurrency($transaction['currency']);
    $transactionEntity->setBin($transaction['bin']);

    try {
        $binResults = file_get_contents('https://lookup.binlist.net/' . $transactionEntity->getBin());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
    $binResults = json_decode($binResults,true);
    if (!validateBin($binResults)) continue;
    $binCountry = new \App\Entities\BinCountry();
    $binCountry->setAlpha2($binResults['country']['alpha2']);
    $commissionCompute->setBinCountry($binCountry);

    echo $commissionCompute->compute($transactionEntity)."\n";
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
