<?php

namespace Suitcore\Repositories;

use Suitcore\Models\SuitSetting;
use Cache;
use Suitcore\Repositories\Contract\SuitSettingRepositoryContract;

class SuitSettingRepository implements SuitSettingRepositoryContract
{   
    protected $mainModel = null;

    /**
     * Default Constructor
     **/
    public function __construct()
    {
        $this->mainModel = new SuitSetting;
    }

	/**
     * Update setting by key.
     * @param string $key
     * @param string $value
     * @return void
     */
    public function updateByKey($key, $value)
    {
        $baseModel = ($this->mainModel ? $this->mainModel->getNew() : new SuitSetting);
        $setting = $baseModel->firstOrNew(['key' => $key]);
        $setting->value = $value;
        $result = $setting->save();
        if ($result) {
            // Begin Update Cache
            Cache::forever('settings', SuitSetting::lists('value', 'key'));
            // Finish Update Cache
        }
    }

    /**
     * Get value of setting.
     * @param string $key
     * @param  string $default
     * @return string
     */
    public function getValue($key, $default = '')
    {
        $baseModel = ($this->mainModel ? $this->mainModel->getNew() : new SuitSetting);
        $setting = Cache::rememberForever('settings', function () use($baseModel) {
            return $baseModel->lists('value', 'key');
        });
        return isset($setting[$key]) ? $setting[$key] : $default;
    }

    /**
     * Save settings
     * @param array $settingArray
     * @return boolean
     */
	public function save($settingArray) {
		$result = false;
		if (is_array($settingArray)) {
            $settings = $settingArray;
			try {
                $definedSettings = SuitSetting::pluck('value', 'key');
                foreach ($definedSettings as $key => $value) {
                    if (!isset($settings[$key])) $settings[$key] = '';
                }
				foreach ($settings as $key => $value) {
					$result = $this->updateByKey($key, $value);
				}
                $definedSettings = SuitSetting::pluck('value', 'key'); 
			} catch (Exception $e) { }
		}
		return $result;
	}
}
