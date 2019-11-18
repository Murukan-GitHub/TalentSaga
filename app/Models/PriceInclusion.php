<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use File;
use DB;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| price_inclusions Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * position_order
| * name
| * is_curated : true, false
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class PriceInclusion extends SuitModel
{
    // MODEL DEFINITION
    public $table = 'price_inclusions';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'position_order',
        'name',
        'is_curated'
    ];

    public $rules = [
        'name' => 'required',
        'is_curated' => 'required'
    ];

    // SCOPES
    public function scopeCurated($query) {
        return $query->where('is_curated', '=', 1);
    }

    // SUITMODEL OVERRIDE
    public function getLabel() {
        return "Price Inclusion";
    }

    public function getUniqueValue() {
        return $this->name;
    }

    public function getUniqueValueColumn() {
        return 'name';
    }

    public function getFormattedValue() {
        return  $this->name;
    }

    public function getOptions() {
        return self::all();
    }

    public function getFormattedValueColumn() {
        return ['name'];
    }

    public function getDefaultOrderColumn() {
        return 'name';
    }

    public function getDefaultOrderColumnDirection() {
        return 'asc';
    }

    public function getAttributeSettings() {
        return [
            "id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "ID"
            ],
            "position_order" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Position Order"
            ],
            "name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "translation" => true,
                "label" => "Name"
            ],
            "is_curated" => [
                "type" => self::TYPE_BOOLEAN,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Is Curated?"
            ],
            "created_at" => [
                "type" => self::TYPE_DATETIME,
                "visible" => false,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Created At"
            ],
            "updated_at" => [
                "type" => self::TYPE_DATETIME,
                "visible" => true,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Updated At"
            ]
        ];
    }

    // EVENT HANDLER
    protected static function boot() {
        parent::boot();

        static::saved(function ($model) {

        });

        static::deleted(function ($model) {
            
        });
    }
}
