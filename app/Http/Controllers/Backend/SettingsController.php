<?php

namespace App\Http\Controllers\Backend;

use Input;
use Redirect;
use View;
use App\Repositories\SettingsRepository;
use Suitcore\Models\SuitSetting;

class SettingsController extends BackendController
{
    // PROPERTIES
    // Default Services / Repository
    protected $settingsRepo;

    // ACTION
    /**
     * Default Constructor.
     *
     * @param SettingRepository $_adsRepo
     */
    public function __construct(SettingsRepository $_settingsRepo)
    {
        $this->setID('E6');
        $this->settingsRepo = $_settingsRepo;
    }

    public function getList()
    {
        $settings = new SuitSetting();
        view()->share('title', 'Settings');
        view()->share('pageTitle', 'Settings');
        return View::make('backend.settings.view')->with('settings', $settings);
    }

    public function postSaveSettings()
    {
        $settings = Input::get('settings', []);
        $this->settingsRepo->save($settings);

        return Redirect::route('backend.settings.view');
    }
}
