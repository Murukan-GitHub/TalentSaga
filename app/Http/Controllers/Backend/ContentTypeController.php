<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\ContentTypeRepository;
use App\Models\ContentType;

class ContentTypeController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param ContentTypeRepository $_baseRepo
     * @param ContentType           $_baseModel
     */
    public function __construct(ContentTypeRepository $_baseRepo, ContentType $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.contenttype';
        $this->viewBaseClosure = 'backend.contenttypes';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
