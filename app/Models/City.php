<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| all_kabkot Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * country_id INT(10) UNSIGNED
| * code VARCHAR(4) NOT NULL
| * name VARCHAR(128) NOT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class City extends SuitModel
{
    // MODEL DEFINITION
    protected $primaryKey = 'id';

    public $table = 'cities';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'code',
        'name',
        'country_id',
        'is_active'
    ];

    public $rules = [
        'name' => 'required',
        'country_id' => 'required',
        'is_active' => 'required'
    ];

    // SCOPES
    public function scopeActive($query) {
        return $query->where('is_active', '=', 1);
    }

    // RELATIONSHIP
    /**
     * Get the country of the city.
     *
     * @return Country|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getLabel()
    {
        return 'City';
    }

    public function getFormattedValue()
    {
        return $this->name . ($this->country ? ', ' . $this->country->name__trans : '');
    }

    public function getFormattedValueColumn()
    {
        return ['name', 'country'];
    }

    public function getOptions()
    {
        return self::all();
    }

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
            'country_id' => [
                'type' => self::TYPE_NUMERIC,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => 'country',
                'label' => 'Country',
                'options' => [],
                'filterable' => true
            ],
            'code' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Code'
            ],
            'name' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Name'
            ],
            "is_active" => [
                "type" => self::TYPE_BOOLEAN,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Is Active?"
            ],
            'created_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => false,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' =>'Created At'
            ],
            'updated_at' => [
                'type' => self::TYPE_DATETIME,
                'visible' => false,
                'formdisplay' => false,
                'required' => false,
                'relation' => null,
                'label' => 'Updated At'
            ]
        ];
    }

    // EVENT HANDLER
    protected static function boot() {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget('all_cities');
            Cache::forget('city_by_'.($model->parent_id ? $model->parent_id : 'all'));
        });

        static::deleted(function ($model) {
            Cache::forget('all_cities');
            Cache::forget('city_by_'.($model->parent_id ? $model->parent_id : 'all'));
        });
    }
}
