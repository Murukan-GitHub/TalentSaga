<?php

namespace App\Models;

use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| articles Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * name varchar(255)
| * code varchar(32)
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class ContentType extends SuitModel
{
    const DYNAMIC_TYPE = 'dynamic';
    const STATIC_TYPE = 'static';

    protected static $dynamicId = null;
    protected static $bufferAttributeSettings = null;

    // MODEL DEFINITION
    public $table = 'content_types';

    public $fillable = [
        'name',
        'code'
    ];

    public $rules = [
        'name' => 'required',
        'code' => 'required'
    ];

    public function scopeDynamicType($query)
    {
        return $query->where('name', static::DYNAMIC_TYPE);
    }

    public function scopeStaticType($query)
    {
        return $query->where('name', static::STATIC_TYPE);
    }

    public function categories()
    {
        return $this->hasMany(ContentCategory::class);
    }

    public static function getDynamicId()
    {
        if (static::$dynamicId === null) {
            static::$dynamicId = static::dynamicType()->first()->id;
        }

        return static::$dynamicId;
    }

    public function getLabel() {
        return "Content Type";
    }

    public function getFormattedValue() {
        return $this->name;
    }

    public function getOptions() {
        return self::all();
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
            "name" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Name"
            ],
            "code" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Code"
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
                "visible" => false,
                "formdisplay" => false,
                "required" => true,
                "relation" => null,
                "label" => "Updated At"
            ]
        ];
    }
}
