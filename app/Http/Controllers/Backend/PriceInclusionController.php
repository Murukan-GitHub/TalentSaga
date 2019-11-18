<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\PriceInclusionRepository;
use App\Models\PriceInclusion;

class PriceInclusionController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param PriceInclusionRepository $_baseRepo
     * @param PriceInclusion           $_baseModel
     */
    public function __construct(PriceInclusionRepository $_baseRepo, PriceInclusion $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.priceinclusion';
        $this->viewBaseClosure = 'backend.priceinclusions';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D6');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
