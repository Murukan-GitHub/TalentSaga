<?php

namespace App\Http\Controllers\Backend;

use App\Models\ContactMessage;
use App\Repositories\ContactMessageRepository;
use Input;
use Menu;
use Redirect;
use Route;
use View;

class ContactMessageController extends BackendController
{
    /**
     * Override Default Constructor.
     *
     * @param  ContactMessageRepository $_baseRepo
     * @param  ContactMessage           $_baseModel
     * @return void
     */
    public function __construct(ContactMessageRepository $_baseRepo, ContactMessage $_baseModel)
    {
        parent::__construct($_baseRepo, $_baseModel);
        $this->routeBaseName = 'backend.contactmessage';
        $this->viewBaseClosure = 'backend.contactmessages';
        $this->viewInstanceName = 'baseObject';
        // page ID
        $this->setID('E4');
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
        // Return
        $menuSetting = [
            'session_token' => csrf_token(),
            'url_detail' => (Route::has($this->routeBaseName . '.show') ? route($this->routeBaseName . '.show',["id"=>"#id#"]) : ''),
            'url_delete' => (Route::has($this->routeBaseName . '.destroy') ? route($this->routeBaseName . '.destroy', ['id' => "#id#"]) : ''),
        ];
        $renderedMenu = View::make(self::$partialView[self::TABLE_MENU], ['menuSetting' => $menuSetting])->render();
        unset($menuSetting['url_delete']);
        $renderedMenu2 = View::make(self::$partialView[self::TABLE_MENU], ['menuSetting' => $menuSetting])->render();
        return $this->baseRepository->jsonDatatable($param, [
            'menu' => $renderedMenu,
            'menu_without_delete' => $renderedMenu2
        ]);
    }

    /**
     * Post Reply a Contact Message
     *
     * @param  integer $id
     *
     * @return [type]     [description]
     */
    public function postReply($id)
    {
        // Reply
        $param   = Input::all();
        $baseObj = $this->model;
        $result  = $this->repository->sendReply($id, $param, $baseObj);
        // Result
        if ($result) {
            $this->notification(self::NOTIFICATION_NOTICE, $this->model->getLabel() . ' Replied', $this->model->getLabel() . ' had replied!');
        } else {
            $this->notification(self::NOTIFICATION_ERROR, 'Can\'t Reply ' . $this->model->getLabel(), $this->model->getLabel() . ' had not replied! An error occured when processing with database or email protocol.');
        }
        // Return
        if (Route::has($this->routeBase . '.show')) {
            return Redirect::route($this->routeBase . '.show', ['id' => $id]);
        } else {
            if (!empty($this->routeDefaultIndex)) {
                return Redirect::route($this->routeDefaultIndex);
            }
            return Redirect::route($this->routeBase . '.index');
        }
    }
}
