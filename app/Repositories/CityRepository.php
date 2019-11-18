<?php

namespace App\Repositories;

use Cache;
use Suitcore\Models\SuitModel;
use App\Models\City;
use Suitcore\Repositories\SuitRepository;

class CityRepository extends SuitRepository
{
    public function __construct()
    {
        $this->mainModel = new City;
    }

    public function cachedList($fetchAll = false, $countryId = 0)
    {
        return Cache::rememberForever(($fetchAll ? 'all_cities' : 'city_by_'.($countryId ? $countryId : 'all')), function () use($fetchAll, $countryId) {
            $cities = null;
            if ($fetchAll) $cities = City::active()->orderBy('name','asc')->get();
            elseif ($countryId) $cities = City::active()->where('country_id', '=', $countryId)->orderBy('name','asc')->get();
            else $cities = City::active()->orderBy('name','asc')->get();

            if ($cities && count($cities) > 0) return $cities;
            return collect([]);
        });
    }
}
