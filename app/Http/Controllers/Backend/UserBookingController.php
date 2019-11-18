<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\UserBookingRepository;
use App\Models\UserBooking;

class UserBookingController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param UserBookingRepository $_baseRepo
     * @param UserBooking           $_baseModel
     */
    public function __construct(UserBookingRepository $_baseRepo, UserBooking $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.userbooking';
        $this->viewBaseClosure = 'backend.userbookings';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('F1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
