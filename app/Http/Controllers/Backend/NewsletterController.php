<?php

namespace App\Http\Controllers\Backend;

use View;
use App\Repositories\NewsletterRepository;
use App\Models\Newsletter;

class NewsletterController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param NewsletterRepository $_baseRepo
     * @param Newsletter           $_baseModel
     */
    public function __construct(NewsletterRepository $_baseRepo, Newsletter $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.newsletter';
        $this->viewBaseClosure = 'backend.newsletters';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('B2');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    public function getPreview($id) {
        $newsletter = Newsletter::find($id);
        if (!$newsletter) return app()->abort(404);
        $previewOnly = true;
        return view('emails.newsletter', compact('newsletter', 'previewOnly'));
    }
}
