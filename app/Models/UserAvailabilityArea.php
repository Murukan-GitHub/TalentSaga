<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| user_profiles Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * user_id INT(10) NOT NULL TO ‘users’
| * city_id NOT NULL TO 'cities'
| * created_at DATETIME NOT NULL
| * updated_at DATETIME NOT NULL
*/
class UserAvailabilityArea extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'user_availability_areas';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'city_id'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'city_id' => 'required|exists:cities,id'
    ];

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return City|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Availability Area';
    }

    /**
     * Get format text name
     *
     * @return string
     */
    public function getFormattedValue()
    {
        return $this->user_id;
    }

    /**
     * Get options of model
     *
     * @return array
     */
    public function getOptions()
    {
        return self::all();
    }

    /**
     * Get Attributes of Setting
     *
     * @return array
     */
    public function getAttributeSettings()
    {
        // default attribute settings of generic model, override for furher use
        return [
            'id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => false,
                'required' => true,
                'relation' => null,
                'label' => 'ID'
            ],
            'user_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'user',
                'label' => 'User',
                "options" => [], // always initiated
                "readonly" => true,
                "initiated" => true
            ],
            'city_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'city',
                'label' => 'City',
                "options" => (new City)->all()->pluck('name','id')
            ],
            'created_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => true,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' =>'Created At'
            ],
            'updated_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => true,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' => 'Updated At'
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {

        });

        static::saved(function ($model) {
            Cache::forget('user_completion_' . $model->user_id);
        });

        static::deleted(function ($model) {
            Cache::forget('user_completion_' . $model->user_id);
        });
    }
}
