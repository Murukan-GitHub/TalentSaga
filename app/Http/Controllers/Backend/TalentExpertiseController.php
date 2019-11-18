<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\TalentExpertiseRepository;
use App\Models\TalentExpertise;

class TalentExpertiseController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param TalentExpertiseRepository $_baseRepo
     * @param TalentExpertise           $_baseModel
     */
    public function __construct(TalentExpertiseRepository $_baseRepo, TalentExpertise $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.talentexpertise';
        $this->viewBaseClosure = 'backend.talentexpertises';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D5');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
