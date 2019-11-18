<?php

namespace App\Http\Controllers\Backend;

use App;
use datetime as datetime;
use Hash;
use Input;
use Route;
use Redirect;
use View;
use App\Repositories\UserRepository;
use App\Repositories\UserProfileRepository;
use App\Models\User;

class UserController extends BackendController
{
    protected $userProfileRepo;

    /**
     * Override Default Constructor.
     *
     * @param UserRepository $_baseRepo
     * @param User           $_baseModel
     */
    public function __construct(UserRepository $_baseRepo, 
        UserProfileRepository $_userProfileRepo,
        User $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.user';
        $this->routeDefaultIndex = 'backend.user.index';
        $this->viewBaseClosure = 'backend.users';
        $this->viewInstanceName = 'baseObject';
        $this->userProfileRepo = $_userProfileRepo;
        // page ID
        $this->setID('C1');
        View::share('routeBaseName', $this->routeBaseName);
        View::share('routeDefaultIndex', $this->routeDefaultIndex);
        View::share('viewBaseClosure', $this->viewBaseClosure);
    }

    /**
     * Display baseModel detail
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function getView($id)
    {
        // Fetch
        view()->share('title', 'Detail');
        $fetchedData = $this->baseRepository->get($id);
        // Related
        $userProfile = null;
        if ($fetchedData['object']) {
            $userProfile = $this->userProfileRepo->getByParameter([
                'user_id' => $fetchedData['object']->id,
                'paginate' => false,
                'perPage' => 1
            ]);
        }
        // Return
        $baseObj = $this->beforeView($fetchedData['object']);
        return view($this->viewBaseClosure . '.show')->with($this->viewInstanceName, $baseObj)
                ->with('userProfile',$userProfile);
    }

    public function getShowprofile($id)
    {
        $user = User::find($id);
        $orders = Order::where('user_id', $id)->get();
        $reviews = Review::where('user_id', $id)->get();

        return View::make('backend.users.profile', array('user' => $user, 'orders' => $orders, 'reviews' => $reviews));
    }

    public function postCreate()
    {
        // Save
        $param = Input::all();
        $baseObj = $this->baseModel;
        $param['password'] = Hash::make($param['password']);
        $date = new datetime();
        $param['registration_date'] = $date;
        $param['last_visit'] = $date;
        $param['status'] = User::STATUS_INACTIVE;
        $param['address_country'] = 'Indonesia';
        $result = $this->baseRepository->create($param, $baseObj);
        // Return
        if ($result) {
            $this->showNotification(self::NOTICE_NOTIFICATION, $baseObj->getLabel().' Created', 'New '.$baseObj->getLabel().' data had been created!');
            if (Route::has($this->routeBaseName.'.show')) {
                return Redirect::route($this->routeBaseName.'.show', ['id' => $baseObj->id]);
            } else {
                if (!empty($this->routeDefaultIndex)) {
                    return Redirect::route($this->routeDefaultIndex);
                }

                return Redirect::route($this->routeBaseName.'.index');
            }
        }

        return Redirect::route($this->routeBaseName.'.create')->with('errors', $baseObj->errors)->withInput($param);
    }

    public function postUpdate($id)
    {
        // Save
        $param = Input::all();
        $baseObj = $this->baseModel->find($id);
        if ($baseObj) {
            if (isset($param['password']) &&
                !empty($param['password']) &&
                isset($param['password_confirm']) &&
                !empty($param['password_confirm']) &&
                ($param['password'] == $param['password_confirm'])) {
                $param['password'] = Hash::make($param['password']);
            } else {
                $param['password'] = $baseObj->password;
            }
        }
        $result = $this->baseRepository->update($id, $param, $baseObj);
        // Return
        if ($result) {
            $this->showNotification(self::NOTICE_NOTIFICATION, $baseObj->getLabel().' Updated', $baseObj->getLabel().' data had been updated!');
            if (Route::has($this->routeBaseName.'.show')) {
                return Redirect::route($this->routeBaseName.'.show', ['id' => $id]);
            } else {
                if (!empty($this->routeDefaultIndex)) {
                    return Redirect::route($this->routeDefaultIndex);
                }

                return Redirect::route($this->routeBaseName.'.index');
            }
        }
        if ($baseObj == null) {
            App::abort(404);
        }

        return Redirect::route($this->routeBaseName.'.update', ['id' => $id])->with('errors', $baseObj->errors)->withInput($param);
    }

    public function getActivate($id)
    {
        $user = User::find($id);
        $user->activate();

        return Redirect::route('backend.user.index');
    }

    public function getDeactivate($id)
    {
        $user = User::find($id);
        $user->deactivate();

        return Redirect::route('backend.user.index');
    }
}
