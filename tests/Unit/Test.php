<?php
namespace Test\Unit;
use App\CommissionComputingService;
use App\Entities\Transaction;
use App\Entities\BinCountry;
use App\Entities\Rate;
use PHPUnit\Framework\TestCase;

class ExpectedErrorTest extends TestCase
{
    const RATES = [
       "CAD" => 1.5603,
       "HKD" => 9.091,
       "ISK" => 162.2,
       "PHP" => 56.864,
       "DKK" => 7.4414,
       "HUF" => 358.88,
       "CZK" => 27.02,
       "AUD" => 1.6372,
       "RON" => 4.8715,
       "SEK" => 10.425,
       "IDR" => 17445.33,
       "INR" => 86.0375,
       "BRL" => 6.6039,
       "RUB" => 92.0825,
       "HRK" => 7.562,
       "JPY" => 123.4,
       "THB" => 36.991,
       "CHF" => 1.079,
       "SGD" => 1.5988,
       "PLN" => 4.4944,
       "BGN" => 1.9558,
       "TRY" => 9.0787,
       "CNY" => 7.9656,
       "NOK" => 10.904,
       "NZD" => 1.7674,
       "ZAR" => 19.4002,
       "USD" => 1.173,
       "MXN" => 25.6582,
       "ILS" => 4.0244,
       "GBP" => 0.90673,
       "KRW" => 1364.68,
       "MYR" => 4.885
    ];

    public function test_process() {
        $commission = $this->computeComission('DK', 45717360, 100.00, 'EUR');
        $this->assertEquals(1, $commission);

        $commission = $this->computeComission('LT', 516793, 50.00, 'USD');
        $this->assertEquals(0.43, $commission);

        $commission = $this->computeComission('JP', 45417360, 10000.00, 'JPY');
        $this->assertEquals(1.63, $commission);

        $commission = $this->computeComission('US', 41417360, 130.00, 'USD');
        $this->assertEquals(2.22, $commission);

        $commission = $this->computeComission('GB', 4745030, 2000.00, 'GBP');
        $this->assertEquals(44.12, $commission);
    }

    public function testRateException () {
        $this->expectExceptionMessage('Rate was not set');
        $transaction = new Transaction();
        $commissionCompute = new CommissionComputingService();
        $commissionCompute->compute($transaction);
    }

    public function testBinCountryException () {
        $this->expectExceptionMessage('BinCountry was not set');
        $transaction = new Transaction();
        $rate = new Rate();
        $commissionCompute = new CommissionComputingService();
        $commissionCompute->setRate($rate);
        $commissionCompute->compute($transaction);
    }

    protected function computeComission ($alpha2, $bin, $amount, $currency) {
        $binCountry = new BinCountry();
        $binCountry->setAlpha2($alpha2);

        $rate = new Rate();
        $rate->setRates(self::RATES);

        $transaction = new Transaction();
        $transaction->setBin($bin);
        $transaction->setAmount($amount);
        $transaction->setCurrency($currency);

        $commissionCompute = new CommissionComputingService();
        $commissionCompute->setBinCountry($binCountry);
        $commissionCompute->setRate($rate);
        return $commissionCompute->compute($transaction);
    }
}
