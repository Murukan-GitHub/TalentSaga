<?php

namespace App\Models;

use Cache;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| currencies Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * code VARCHAR(4) NOT NULL
| * metric VARCHAR(4) NOT NULL
| * name VARCHAR(128) NOT NULL
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class Currency extends SuitModel
{
    // MODEL DEFINITION
    protected $primaryKey = 'id';

    public $table = 'currencies';

    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'code',
        'metric',
        'name',
        'is_active'
    ];

    public $rules = [
        'code' => 'required',
        'metric' => 'required',
        'name' => 'required',
        'is_active' => 'required'
    ];

    public function scopeActive($query) {
        return $query->where('is_active', '=', 1);
    }

    public function getLabel()
    {
        return 'Currency';
    }

    public function getFormattedValue()
    {
        return $this->name . ' (' . $this->code . ')';
    }

    public function getFormattedValueColumn()
    {
        return ['name', 'code'];
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
            'code' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Code'
            ],
            'metric' => [
                'type' => self::TYPE_TEXT,
                'visible' => true,
                'formdisplay' => true,
                'required' => true,
                'relation' => null,
                'label' => 'Metric'
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
            Cache::forget('all_currency');
        });

        static::deleted(function ($model) {
            Cache::forget('all_currency');
        });
    }
}
