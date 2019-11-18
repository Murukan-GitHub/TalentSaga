<?php

namespace App\Http\Controllers\Backend;

use App\Repositories\NewsletterSubscriberRepository;
use App\Models\NewsletterSubscriber;
use View;

class NewsletterSubscriberController extends BackendController
{
    /**
     * Override Default Constructor
     * @param  NewsletterSubscriberRepository $baseRepo
     * @param  NewsletterSubscriber $baseModel
     * @return void
     */
    public function __construct(NewsletterSubscriberRepository $baseRepo, NewsletterSubscriber $baseModel){
        parent::__construct($baseRepo, $baseModel);
        $this->routeBaseName = "backend.newslettersubscribers";
        $this->routeDefaultIndex = "backend.newslettersubscribers.index";
        $this->viewBaseClosure = "backend.newslettersubscribers";
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->pageId = 'B1';
        View::share('pageId', $this->pageId);
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }
}
