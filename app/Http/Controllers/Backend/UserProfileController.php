<?php

namespace App\Http\Controllers\Backend;

use View;
use Input;
use App\Repositories\UserProfileRepository;
use App\Models\UserProfile;

class UserProfileController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param UserProfileRepository $_baseRepo
     * @param UserProfile           $_baseModel
     */
    public function __construct(UserProfileRepository $_baseRepo, UserProfile $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel, 'user');
        $this->routeBaseName = 'backend.userprofile';
        $this->viewBaseClosure = 'backend.userprofiles';
        $this->viewInstanceName = 'baseObject';
        $this->routeDefaultIndex = "backend.user.show";
        // page ID
        $this->setID('C1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    /**
     * Display baseModel create form
     * @param  
     * @return \Illuminate\View\View
     */
    public function getCreate()
    {
        $baseObj = $this->baseModel;
        $userId = Input::get('user_id');
        if ($userId && $userId > 0) {
            $baseObj->user_id = $userId;
            return view($this->viewBaseClosure . '.create')->with($this->viewInstanceName, $baseObj);
        }
        return $this->returnToRootIndex($baseObj);
    }
}
