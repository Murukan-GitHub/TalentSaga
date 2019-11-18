<?php

namespace Suitcore\Currency;

trait ModelCurrencyTrait
{
    public function asCurrency($field)
    {
        $number = $this->{$field};

        if (method_exists($this, 'formatCurrency')) {
            return $this->formatCurrency($number);
        }

        if (function_exists('asCurrency')) {
            return asCurrency($number);
        }
        
        return 'Rp. '.number_format($number, 2, '.', ',');
    }
}
