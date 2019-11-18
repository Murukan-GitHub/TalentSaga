<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\TeamRepository;
use App\Models\Team;

class TeamController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param TeamRepository $_baseRepo
     * @param Team           $_baseModel
     */
    public function __construct(TeamRepository $_baseRepo, Team $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.team';
        $this->viewBaseClosure = 'backend.teams';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E3');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
