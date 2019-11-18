<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cache;
use File;
use DB;
use Suitcore\Models\SuitModel;

/*
|--------------------------------------------------------------------------
| talent_expertises Table Structure
|--------------------------------------------------------------------------
| * id INT(10) NOT NULL
| * talent_category_id --> talent_categories
| * position_order
| * slug
| * name
| * is_curated : true, false
| * created_at TIMESTAMP
| * updated_at TIMESTAMP
*/
class TalentExpertise extends SuitModel
{
    use Sluggable;

    // MODEL DEFINITION
    public $table = 'talent_expertises';
    protected static $bufferAttributeSettings = null;

    public $fillable = [
        'talent_category_id',
        'position_order',
        'slug',
        'name',
        'is_curated'
    ];

    public $rules = [
        'talent_category_id' => 'required',
        // 'slug' => 'required',
        'name' => 'required',
        'is_curated' => 'required'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // SCOPES
    public function scopeCurated($query) {
        return $query->where('is_curated', '=', 1);
    }

    // RELATIONSHIP
    /**
     * Get the parent that owns the category.
     * @return \App\Models\TalentCategory|null
     */
    public function category()
    {
        return $this->belongsTo(TalentCategory::class, 'talent_category_id');
    }

    // SUITMODEL OVERRIDE
    public function getLabel() {
        return "Talent Expertises";
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
            "talent_category_id" => [
                "type" => self::TYPE_NUMERIC,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => "category",
                "label" => "Talent Category",
                "options" => (new TalentCategory)->orderBy('position_order','asc')->orderBy('name','asc')->pluck('name','id')->toArray(),
                "filterable" => true
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
                "label" => "Name",
                "translation" => true
            ],
            "is_curated" => [
                "type" => self::TYPE_BOOLEAN,
                "visible" => true,
                "formdisplay" => true,
                "required" => true,
                "relation" => null,
                "label" => "Is Curated?"
            ],
            "slug" => [
                "type" => self::TYPE_TEXT,
                "visible" => true,
                "formdisplay" => true,
                "required" => false,
                "relation" => null,
                "label" => "Slug"
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
