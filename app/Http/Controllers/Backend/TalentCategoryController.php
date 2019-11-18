<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\TalentCategoryRepository;
use App\Models\TalentCategory;

class TalentCategoryController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param TalentCategoryRepository $_baseRepo
     * @param TalentCategory           $_baseModel
     */
    public function __construct(TalentCategoryRepository $_baseRepo, TalentCategory $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.talentcategory';
        $this->viewBaseClosure = 'backend.talentcategories';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('D4');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
