<?php

namespace Suitcore\Models;

use Cache;

/*
|--------------------------------------------------------------------------
| settings Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * key VARCHAR(255) NOT NULL
| * value VARCHAR(255) NOT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class SuitSetting extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'settings';

    protected $primaryKey = 'key';

    public $fillable = [
        'key',
        'value'
    ];

    public $rules = [
        'key' => 'required'
    ];

    // SERVICES (temporary included in model, since may be there is frontend module access these static functions)
    /**
     * Update setting by key.
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function updateByKey($key, $value)
    {
        $setting = SuitSetting::firstOrNew(['key' => $key]);
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
    public static function getValue($key, $default = '')
    {
        $setting = Cache::rememberForever('settings', function () {
            return SuitSetting::lists('value', 'key');
        });
        return isset($setting[$key]) ? $setting[$key] : $default;
    }
}
