<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\FaqRepository;
use App\Models\Faq;

class FaqController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param FaqRepository $_baseRepo
     * @param Faq           $_baseModel
     */
    public function __construct(FaqRepository $_baseRepo, Faq $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.faq';
        $this->viewBaseClosure = 'backend.faqs';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E5');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
