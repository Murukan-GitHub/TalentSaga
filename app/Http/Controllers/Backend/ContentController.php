<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\ContentRepository;
use App\Models\Content;

class ContentController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param ContentRepository $_baseRepo
     * @param Content           $_baseModel
     */
    public function __construct(ContentRepository $_baseRepo, Content $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.content';
        $this->viewBaseClosure = 'backend.contents';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E2');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    protected function processRequest($request)
    {
        // $slug = isset( $request['slug'] ) && $request['slug'] ?: ($request['title'] ?: '');
        // $request['slug'] = str_slug($slug);
        return parent::processRequest($request);
    }

    public function getCreate()
    {
        view()->share('hiddenInputs', ['slug']);
        return parent::getCreate();
    }
}
