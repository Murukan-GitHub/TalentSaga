<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\FaqCategoryRepository;
use App\Models\FaqCategory;

class FaqCategoryController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param FaqCategoryRepository $_baseRepo
     * @param FaqCategory           $_baseModel
     */
    public function __construct(FaqCategoryRepository $_baseRepo, FaqCategory $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.faqcategory';
        $this->viewBaseClosure = 'backend.faqcategories';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E4');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    protected function processRequest($request)
    {
        $slug = $request['slug'] ?: ($request['name'] ?: '');
        $request['slug'] = str_slug($slug);
        return parent::processRequest($request);
    }
}
