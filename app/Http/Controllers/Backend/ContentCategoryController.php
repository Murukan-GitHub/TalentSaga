<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\ContentCategoryRepository;
use App\Models\ContentCategory;

class ContentCategoryController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param ContentCategoryRepository $_baseRepo
     * @param ContentCategory           $_baseModel
     */
    public function __construct(ContentCategoryRepository $_baseRepo, ContentCategory $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.contentcategory';
        $this->viewBaseClosure = 'backend.contentcategories';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E1');
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
