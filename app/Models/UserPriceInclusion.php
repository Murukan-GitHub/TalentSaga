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
| * price_inclusion_id NOT NULL TO 'price_inclusions'
| * created_at DATETIME NOT NULL
| * updated_at DATETIME NOT NULL
*/
class UserPriceInclusion extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'user_price_inclusions';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'user_id',
        'price_inclusion_id'
    ];

    public $rules = [
        'user_id' => 'required|exists:users,id',
        'price_inclusion_id' => 'required|exists:price_inclusions,id'
    ];

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return PriceInclusion|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priceInclusion()
    {
        return $this->belongsTo(PriceInclusion::class);
    }

    /**
     * Get label name of Model
     * @return string
     */
    public function getLabel()
    {
        return 'User Price Inclusion';
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
            'price_inclusion_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'priceInclusion',
                'label' => 'Price Inclusion',
                "options" => (new PriceInclusion)->all()->pluck('name','id')
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

        });
    }
}
