<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\CityRepository;
use App\Models\City;

class CityController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param CityRepository $_baseRepo
     * @param City           $_baseModel
     */
    public function __construct(CityRepository $_baseRepo, City $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.city';
        $this->viewBaseClosure = 'backend.cities';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D3');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
