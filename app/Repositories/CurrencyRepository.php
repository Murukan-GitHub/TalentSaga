<?php

namespace App\Repositories;

use Cache;
use Suitcore\Models\SuitModel;
use App\Models\Currency;
use Suitcore\Repositories\SuitRepository;

class CurrencyRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new Currency;
    }

    public function cachedList()
    {
        return Cache::rememberForever('all_currency', function () {
            $currencies = Currency::active()->orderBy('code','asc')->get();

            if ($currencies && count($currencies) > 0) return $currencies;
            return collect([]);
        });
    }
}
