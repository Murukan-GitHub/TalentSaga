<?php

namespace App\Http\Controllers\Frontend;

use View, Redirect, Auth, App, Input;
use App\Models\NewsletterSubscriber;
use App\Repositories\NewsletterSubscriberRepository;

class NewsletterController extends BaseController {
    /** ATTRIBUTES **/
    protected $newsletterSubscriberRepo;

    /** METHODS / ACTIONS **/
    public function __construct(NewsletterSubscriberRepository $_newsletterSubscriberRepo)
    {
        parent::__construct();
        $this->newsletterSubscriberRepo = $_newsletterSubscriberRepo;
    }

    /**
     * Save subscribing or save enable news letter settings
     *
     * @return Redirect
     **/
    public function updateSubscriber(){
        $user = Auth::user();

        $rules = [
            'email' => 'required|email'
        ];
        $this->validate(request(), $rules);
        $param = Input::all();

        if ($user) {
            if (isset($param['newsletter_subscribe'])) {
                $user->newsletter = (!empty($param['newsletter_subscribe']) && $param['newsletter_subscribe'] == 1 ? 1 : 0);
                $result = $user->save();
            }
            if (!isset($param['email']) || empty($param['email'])) {
                $param['email'] = $user->email;
                $param['name'] = $user->name;   
            }
        } 

        if (isset($param['email']) && !empty($param['email'])) {
            $newsletterSubscriber = $this->newsletterSubscriberRepo->getByEmail($param['email']);
            if (!$newsletterSubscriber) {
                $newsletterSubscriber = new NewsletterSubscriber;
                if (!isset($param['name']) || empty($param['name'])) {
                    $param['name'] = explode('@', $param['email'])[0];
                }
                $result = $this->newsletterSubscriberRepo->create($param, $newsletterSubscriber);
                if ($result) {
                    // notify
                    $this->notifySuccess(trans('messages.newsletter.success'));
                } else {
                    // notify
                    $this->notifyError(trans('messages.newsletter.failed'));
                }
            } else {
                // notify already subscriber
                $this->notifyError(trans('messages.newsletter.already_subscribed'));
            }
        } else {
            $this->notifyError(trans('messages.newsletter.incomplete'));
        }

        return Redirect::route('frontend.home');   
    }
}
