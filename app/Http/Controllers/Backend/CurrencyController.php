<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\CurrencyRepository;
use App\Models\Currency;

class CurrencyController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param CurrencyRepository $_baseRepo
     * @param Currency           $_baseModel
     */
    public function __construct(CurrencyRepository $_baseRepo, Currency $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.currency';
        $this->viewBaseClosure = 'backend.currencies';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D7');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
