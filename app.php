<?php
require_once 'vendor/autoload.php';

use App\RateLoader;
use App\BinLoader;
use App\CommissionComputingService;
use GuzzleHttp\Client;
use App\Entities\Transaction;

$dataString = file_get_contents($argv[1]);

if (!$dataString) {
    throw new \Exception("file is empty");
}

$client = new Client();
$transactions = explode("\n", $dataString);
$ratesData = null;
$rateLoader = new RateLoader($client);
$binLoader = new Binloader($client);
$rate = $rateLoader->getRatesDate();

$commissionCompute = new CommissionComputingService();
$commissionCompute->setRate($rate);

foreach ($transactions as $transaction) {
    $transaction = json_decode($transaction, true);

    if (!validateTransaction($transaction)) {
        echo "Wrong transaction format \n";
        continue;
    }

    $transactionEntity = getTransaction($transaction);
    $binCountry = $binLoader->getBinDate($transactionEntity->getBin());
    $commissionCompute->setBinCountry($binCountry);
    echo $commissionCompute->compute($transactionEntity) . "\n";
}

function validateTransaction(?array $row): bool
{
    if (!$row || !array_key_exists('amount', $row) || !array_key_exists('currency', $row)
        || !array_key_exists('bin', $row)) {
        return false;
    }
    return true;
}

function getTransaction(array $transaction): Transaction
{
    $transactionEntity = new Transaction();
    $transactionEntity->setAmount($transaction['amount']);
    $transactionEntity->setCurrency($transaction['currency']);
    $transactionEntity->setBin($transaction['bin']);
    return $transactionEntity;
}

