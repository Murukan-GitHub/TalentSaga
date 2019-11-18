<?php

namespace App\Http\Controllers\Backend;

use View;
use Input;
use Route;
use App\Repositories\UserPriceInclusionRepository;
use App\Models\UserPriceInclusion;

class UserPriceInclusionController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param UserPriceInclusionRepository $_baseRepo
     * @param UserPriceInclusion           $_baseModel
     */
    public function __construct(UserPriceInclusionRepository $_baseRepo, UserPriceInclusion $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel, 'user');
        $this->routeBaseName = 'backend.userpriceinclusion';
        $this->viewBaseClosure = 'backend.userpriceinclusions';
        $this->viewInstanceName = 'baseObject';
        $this->routeDefaultIndex = "backend.user.show";
        // page ID
        $this->setID('C1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    /**
     * Return json list of contentType
     * @param  
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postIndexJson() {
        // Parameter
        $param = Input::all();
        // Filter Parameter
        $specificFilter = [];
        if (isset($param['user_id'])) $specificFilter['user_id'] = $param['user_id'];
        // Return
        $renderedMenu = "";
        if (Route::has($this->routeBaseName . '.show'))
            $renderedMenu .= "<a href='".route($this->routeBaseName . '.show',["id"=>"#id#"])."' class='btn btn--lime'><span class='fa fa-fw fa-search'></span></a> &nbsp; ";
        if (Route::has($this->routeBaseName . '.edit'))
            $renderedMenu .= "<a href='".route($this->routeBaseName . '.edit',["id"=>"#id#"])."' class='btn btn--blue'><span class='fa fa-fw fa-pencil'></span></a> &nbsp; ";
        if (Route::has($this->routeBaseName . '.destroy'))
            $renderedMenu .= "<form method='post' action='" . route($this->routeBaseName . '.destroy', ['id' => "#id#"]) . "'><input type='hidden' name='_token' value='" . $param['_token'] . "'><button type='submit' class='btn btn--red' onClick='return confirm(\"Are you sure?\");'><span class='fa fa-fw fa-remove'></span></button></form>";
        return $this->baseRepository->jsonDatatable($param, [
            'menu' => $renderedMenu
        ], $specificFilter);
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
