<?php
namespace App\classes;

class CurrencyCalculator
{
    public function __construct(private float $sourceCurrencyRate, private float $targetCurrencyRate){}

    public function scale() : float
    {
        return $this->sourceCurrencyRate / $this->targetCurrencyRate;
    }

    // ...
}
?>