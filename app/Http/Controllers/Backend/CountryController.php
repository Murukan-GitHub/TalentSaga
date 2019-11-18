<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\CountryRepository;
use App\Models\Country;

class CountryController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param CountryRepository $_baseRepo
     * @param Country           $_baseModel
     */
    public function __construct(CountryRepository $_baseRepo, Country $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.country';
        $this->viewBaseClosure = 'backend.countries';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
